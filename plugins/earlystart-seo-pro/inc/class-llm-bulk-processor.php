<?php
/**
 * LLM Bulk Processor
 * Queue and process bulk schema generation using WP Cron
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_LLM_Bulk_Processor
{
    const QUEUE_OPTION = 'earlystart_llm_bulk_queue';
    const STATUS_OPTION = 'earlystart_llm_bulk_status';
    const CRON_HOOK = 'earlystart_llm_process_queue';
    const STALE_PROCESSING_SECONDS = 10 * MINUTE_IN_SECONDS;
    const MAX_PROCESSING_ATTEMPTS = 3;
    
    public function __construct() {
        // Register cron hook
        add_action(self::CRON_HOOK, [$this, 'process_next_item']);
        
        // Register AJAX handlers
        add_action('wp_ajax_earlystart_bulk_generate_start', [$this, 'ajax_start_bulk']);
        add_action('wp_ajax_earlystart_bulk_generate_status', [$this, 'ajax_get_status']);
        add_action('wp_ajax_earlystart_bulk_generate_cancel', [$this, 'ajax_cancel']);
    }
    
    /**
     * Queue posts for bulk generation
     */
    public function queue_posts($post_ids, $type = 'schema') {
        $post_ids = array_values(array_unique(array_filter(array_map('absint', (array) $post_ids))));
        $type = in_array($type, ['schema', 'amenities'], true) ? $type : 'schema';
        $existing_status = self::get_status();
        $queue = !empty($existing_status['in_progress']) ? get_option(self::QUEUE_OPTION, []) : [];
        $queued_keys = [];

        foreach ($queue as $item) {
            $queued_post_id = absint($item['post_id'] ?? 0);
            $queued_type = sanitize_key($item['type'] ?? 'schema');
            if ($queued_post_id) {
                $queued_keys[$queued_post_id . ':' . $queued_type] = true;
            }
        }

        $queued_count = 0;
        foreach ($post_ids as $post_id) {
            $queue_key = $post_id . ':' . $type;
            if (isset($queued_keys[$queue_key])) {
                continue;
            }

            $queue[] = [
                'post_id' => $post_id,
                'type' => $type,
                'status' => 'pending',
                'queued_at' => current_time('mysql')
            ];
            $queued_keys[$queue_key] = true;
            $queued_count++;
        }

        update_option(self::QUEUE_OPTION, $queue);

        // Update status
        $status = [
            'total' => count($queue),
            'completed' => $this->count_queue_status($queue, 'completed'),
            'failed' => $this->count_queue_status($queue, 'failed'),
            'pending' => $this->count_queue_status($queue, 'pending'),
            'processing' => $this->count_queue_status($queue, 'processing'),
            'in_progress' => true,
            'started_at' => !empty($existing_status['in_progress']) && !empty($existing_status['started_at'])
                ? $existing_status['started_at']
                : current_time('mysql')
        ];
        update_option(self::STATUS_OPTION, $status);

        // Schedule first processing
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            wp_schedule_single_event(time() + 5, self::CRON_HOOK);
        }

        return $queued_count;
    }
    
    /**
     * Process next item in queue
     */
    public function process_next_item() {
        $queue = get_option(self::QUEUE_OPTION, []);
        
        if (empty($queue)) {
            $this->mark_complete();
            return;
        }

        $queue = $this->recover_stale_processing_items($queue);
        $this->sync_status_counts($queue);
        
        // Find next pending item
        $next_index = null;
        foreach ($queue as $index => $item) {
            if ($item['status'] === 'pending') {
                $next_index = $index;
                break;
            }
        }
        
        if ($next_index === null) {
            if ($this->count_queue_status($queue, 'processing') > 0) {
                if (!wp_next_scheduled(self::CRON_HOOK)) {
                    wp_schedule_single_event(time() + 60, self::CRON_HOOK);
                }
                return;
            }

            $this->mark_complete();
            return;
        }
        
        $item = $queue[$next_index];
        $queue[$next_index]['status'] = 'processing';
        $queue[$next_index]['processing_started_at'] = current_time('timestamp');
        $queue[$next_index]['attempts'] = absint($queue[$next_index]['attempts'] ?? 0) + 1;
        update_option(self::QUEUE_OPTION, $queue);
        
        // Process the item
        $success = $this->generate_for_post($item['post_id'], $item['type']);
        
        // Update queue
        $queue[$next_index]['status'] = $success ? 'completed' : 'failed';
        $queue[$next_index]['completed_at'] = current_time('mysql');
        unset($queue[$next_index]['processing_started_at']);
        update_option(self::QUEUE_OPTION, $queue);
        
        $this->sync_status_counts($queue);

        if (!$this->has_pending_items($queue)) {
            if ($this->count_queue_status($queue, 'processing') > 0) {
                if (!wp_next_scheduled(self::CRON_HOOK)) {
                    wp_schedule_single_event(time() + 60, self::CRON_HOOK);
                }
                return;
            }

            $this->mark_complete();
            return;
        }

        // Schedule next processing (with rate limiting)
        $delay = 5; // 5 seconds between requests
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            wp_schedule_single_event(time() + $delay, self::CRON_HOOK);
        }
    }
    
    /**
     * Generate schema/SEO for a post
     */
    private function generate_for_post($post_id, $type) {
        global $earlystart_llm_client;
        
        if (!$earlystart_llm_client) {
            return false;
        }
        
        try {
            if ($type === 'schema') {
                // Get appropriate schema type for post
                $schema_type = $this->detect_schema_type($post_id);
                
                // Generate schema data
                $result = $earlystart_llm_client->generate_schema_data($post_id, $schema_type, []);
                
                if (!is_wp_error($result)) {
                    $this->upsert_schema_row($post_id, '_earlystart_schema_data', $schema_type, $result);
                    $this->upsert_schema_row($post_id, '_earlystart_post_schemas', $schema_type, $result);
                    clean_post_cache($post_id);
                    return true;
                }
            }
            elseif ($type === 'amenities') {
                // Tier 5: Safety Amenities Extraction
                $amenities = $earlystart_llm_client->generate_amenities_data($post_id);
                
                if (is_wp_error($amenities)) {
                     earlystart_debug_log('[Chroma Bulk] Amenities Error: ' . $amenities->get_error_message());
                     return false;
                }
                
                if (is_array($amenities)) {
                    update_post_meta($post_id, '_earlystart_amenities', $amenities);
                    return true;
                }
            }
        } catch (Exception $e) {
            earlystart_debug_log('[Chroma Bulk] Error processing post ' . $post_id . ': ' . $e->getMessage());
        }
        
        return false;
    }

    /**
     * Insert or update a generated schema row in a post meta collection.
     *
     * @param int    $post_id Post ID.
     * @param string $meta_key Post meta key.
     * @param string $schema_type Schema.org type.
     * @param array  $schema_data Generated schema fields.
     */
    private function upsert_schema_row($post_id, $meta_key, $schema_type, $schema_data) {
        $schemas = get_post_meta($post_id, $meta_key, true);
        if (!is_array($schemas)) {
            $schemas = [];
        }

        $updated = false;
        foreach ($schemas as &$schema) {
            $row_type = $schema['type'] ?? ($schema['@type'] ?? '');
            if ($row_type === $schema_type) {
                $schema = [
                    'type' => $schema_type,
                    'data' => $schema_data,
                ];
                $updated = true;
                break;
            }
        }
        unset($schema);

        if (!$updated) {
            $schemas[] = [
                'type' => $schema_type,
                'data' => $schema_data,
            ];
        }

        update_post_meta($post_id, $meta_key, $schemas);
    }
    
    /**
     * Detect best schema type for post
     */
    private function detect_schema_type($post_id) {
        $post_type = get_post_type($post_id);
        $title = strtolower(get_the_title($post_id));
        $slug = strtolower(get_post_field('post_name', $post_id));
        
        $type_map = [
            'location' => 'MedicalClinic',
            'post' => 'Article',
            'page' => 'Article',
            'team_member' => 'Person',
            'job_listing' => 'JobPosting',
            'event' => 'Event'
        ];

        if ($post_type === 'program') {
            if (strpos($title, 'speech') !== false || strpos($slug, 'speech') !== false) {
                return 'Service';
            }

            if (
                strpos($title, 'occupational') !== false ||
                strpos($slug, 'occupational') !== false ||
                preg_match('/(^|-)ot($|-)/', $slug)
            ) {
                return 'OccupationalTherapy';
            }

            if (strpos($title, 'aba') !== false || strpos($slug, 'aba') !== false) {
                return 'Service';
            }

            return 'Service';
        }
        
        return $type_map[$post_type] ?? 'Article';
    }
    
    /**
     * Mark processing as complete
     */
    private function mark_complete() {
        $status = get_option(self::STATUS_OPTION, []);
        $status['in_progress'] = false;
        $status['pending'] = 0;
        $status['processing'] = 0;
        $status['completed_at'] = current_time('mysql');
        update_option(self::STATUS_OPTION, $status);
        delete_option(self::QUEUE_OPTION);
        
        // Clear cron
        wp_clear_scheduled_hook(self::CRON_HOOK);
    }

    /**
     * Count queue items with a specific status.
     *
     * @param array $queue Queue rows.
     * @param string $status Status to count.
     * @return int
     */
    private function count_queue_status($queue, $status) {
        $count = 0;
        foreach ((array) $queue as $item) {
            if (($item['status'] ?? '') === $status) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Requeue or fail processing rows that were abandoned by an interrupted cron run.
     *
     * @param array $queue Queue rows.
     * @return array
     */
    private function recover_stale_processing_items($queue) {
        $changed = false;
        $now = current_time('timestamp');

        foreach ((array) $queue as $index => $item) {
            if (($item['status'] ?? '') !== 'processing') {
                continue;
            }

            $started_at = absint($item['processing_started_at'] ?? 0);
            if (!$started_at || ($now - $started_at) < self::STALE_PROCESSING_SECONDS) {
                continue;
            }

            $attempts = absint($item['attempts'] ?? 1);
            if ($attempts >= self::MAX_PROCESSING_ATTEMPTS) {
                $queue[$index]['status'] = 'failed';
                $queue[$index]['completed_at'] = current_time('mysql');
                $queue[$index]['error'] = 'Processing timed out after repeated attempts.';
            } else {
                $queue[$index]['status'] = 'pending';
            }

            unset($queue[$index]['processing_started_at']);
            $changed = true;
        }

        if ($changed) {
            update_option(self::QUEUE_OPTION, $queue);
        }

        return $queue;
    }

    /**
     * Keep progress counters aligned with queue row statuses.
     *
     * @param array $queue Queue rows.
     * @return void
     */
    private function sync_status_counts($queue) {
        $status = get_option(self::STATUS_OPTION, []);
        $status['total'] = count((array) $queue);
        $status['completed'] = $this->count_queue_status($queue, 'completed');
        $status['failed'] = $this->count_queue_status($queue, 'failed');
        $status['processing'] = $this->count_queue_status($queue, 'processing');
        $status['pending'] = $this->count_queue_status($queue, 'pending');

        update_option(self::STATUS_OPTION, $status);
    }

    /**
     * Determine whether any queue items remain pending.
     *
     * @param array $queue Queue rows.
     * @return bool
     */
    private function has_pending_items($queue) {
        foreach ((array) $queue as $item) {
            if (($item['status'] ?? '') === 'pending') {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Get queue status
     */
    public static function get_status() {
        return wp_parse_args((array) get_option(self::STATUS_OPTION, []), [
            'total' => 0,
            'completed' => 0,
            'failed' => 0,
            'pending' => 0,
            'processing' => 0,
            'in_progress' => false,
            'started_at' => '',
            'completed_at' => '',
            'cancelled' => false,
            'cancelled_at' => ''
        ]);
    }
    
    /**
     * Get current queue
     */
    public static function get_queue() {
        return get_option(self::QUEUE_OPTION, []);
    }
    
    /**
     * Cancel bulk processing
     */
    public function cancel() {
        wp_clear_scheduled_hook(self::CRON_HOOK);
        delete_option(self::QUEUE_OPTION);
        
        $status = get_option(self::STATUS_OPTION, []);
        $status['in_progress'] = false;
        $status['pending'] = 0;
        $status['processing'] = 0;
        $status['cancelled'] = true;
        $status['cancelled_at'] = current_time('mysql');
        update_option(self::STATUS_OPTION, $status);
    }
    
    /**
     * Detect content gaps across site
     */
    public static function detect_gaps($post_types = ['location', 'program']) {
        $gaps = [];
        
        $posts = get_posts([
            'post_type' => $post_types,
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ]);
        
        foreach ($posts as $post) {
            $schema = get_post_meta($post->ID, '_earlystart_schema_data', true);
            if (empty($schema) || !is_array($schema)) {
                $schema = get_post_meta($post->ID, '_earlystart_post_schemas', true);
            }
            $missing = [];
            
            // Check for critical missing fields
            if (empty($schema)) {
                $missing[] = 'No schema at all';
            } else {
                // Check specific fields
                foreach ($schema as $s) {
                    $data = $s['data'] ?? [];
                    if (empty($data['description'])) $missing[] = 'description';
                    if (empty($data['telephone']) && empty($data['phone'])) $missing[] = 'telephone';
                    if (empty($data['geo_lat']) && empty($data['geo'])) $missing[] = 'geo coordinates';
                    if (empty($data['aggregateRating'])) $missing[] = 'aggregateRating';
                }
            }
            
            // Check GMB sync
            $last_sync = get_post_meta($post->ID, '_gmb_last_sync', true);
            if (empty($last_sync)) {
                $missing[] = 'GMB not synced';
            }
            
            if (!empty($missing)) {
                $gaps[$post->ID] = [
                    'title' => $post->post_title,
                    'post_type' => $post->post_type,
                    'missing' => array_unique($missing)
                ];
            }
        }
        
        return $gaps;
    }
    
    /**
     * AJAX: Start bulk generation
     */
    public function ajax_start_bulk() {
        check_ajax_referer('earlystart_seo_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }
        
        $post_ids = isset($_POST['post_ids']) ? array_map('absint', (array) wp_unslash($_POST['post_ids'])) : [];
        $type = isset($_POST['type']) ? sanitize_key(wp_unslash($_POST['type'])) : 'schema';
        
        if (empty($post_ids)) {
            wp_send_json_error(['message' => 'No posts selected']);
        }

        $post_ids = array_values(array_filter($post_ids, function($post_id) {
            return current_user_can('edit_post', $post_id);
        }));

        if (empty($post_ids)) {
            wp_send_json_error(['message' => 'No editable posts selected']);
        }
        
        $count = $this->queue_posts($post_ids, $type);
        
        wp_send_json_success([
            'message' => "Queued $count new posts for processing",
            'queued' => $count
        ]);
    }
    
    /**
     * AJAX: Get status
     */
    public function ajax_get_status() {
        check_ajax_referer('earlystart_seo_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }
        
        wp_send_json_success([
            'status' => self::get_status(),
            'queue' => self::get_queue()
        ]);
    }
    
    /**
     * AJAX: Cancel
     */
    public function ajax_cancel() {
        check_ajax_referer('earlystart_seo_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }
        
        $this->cancel();
        
        wp_send_json_success(['message' => 'Cancelled']);
    }
}

// Initialize
new earlystart_LLM_Bulk_Processor();


