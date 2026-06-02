<?php
/**
 * IndexNow Integration
 * Automatically notifies search engines when content is updated
 * 
 * @package earlystart_Excellence
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_IndexNow
{
    private static $api_url = 'https://api.indexnow.org/IndexNow';
    
    public static function init()
    {
        add_action('init', [__CLASS__, 'add_rewrite_rule']);
        add_filter('query_vars', [__CLASS__, 'add_query_vars']);
        add_action('template_redirect', [__CLASS__, 'maybe_serve_key_file']);
        add_action('transition_post_status', [__CLASS__, 'on_post_status_transition'], 10, 3);
        add_action('admin_init', [__CLASS__, 'check_key_file']);
    }

    /**
     * Determine whether IndexNow notifications are enabled.
     */
    private static function is_enabled()
    {
        return get_option('earlystart_enable_indexnow', 'yes') === 'yes';
    }

    /**
     * Return the saved IndexNow key, creating one when needed.
     */
    private static function get_key()
    {
        $key = (string) get_option('earlystart_indexnow_key', '');
        if ($key === '') {
            $key = wp_generate_password(32, false);
            update_option('earlystart_indexnow_key', $key, false);
        }

        return preg_replace('/[^A-Za-z0-9_-]/', '', $key);
    }

    /**
     * Register a pretty key-file route for future rewrite flushes.
     */
    public static function add_rewrite_rule()
    {
        add_rewrite_rule('^([A-Za-z0-9_-]{8,128})\.txt$', 'index.php?earlystart_indexnow_key_file=$matches[1]', 'top');
    }

    /**
     * Register the virtual key-file query var.
     */
    public static function add_query_vars($vars)
    {
        $vars[] = 'earlystart_indexnow_key_file';
        return $vars;
    }

    /**
     * Handle post status changes
     */
    public static function on_post_status_transition($new_status, $old_status, $post)
    {
        if (!self::is_enabled()) {
            return;
        }

        // Only trigger on publish or update of published posts
        if ($new_status !== 'publish') {
            return;
        }

        // Avoid triggering on autosaves/revisions
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (wp_is_post_revision($post->ID)) {
            return;
        }

        if (!is_post_type_viewable(get_post_type_object($post->post_type))) {
            return;
        }

        $permalink = get_permalink($post->ID);
        if (!$permalink) {
            return;
        }

        self::notify_indexnow($permalink);
    }

    /**
     * Notify IndexNow API
     */
    public static function notify_indexnow($url)
    {
        if (!self::is_enabled()) {
            return false;
        }

        $url = esc_url_raw((string) $url, ['http', 'https']);
        $host = strtolower((string) parse_url(home_url(), PHP_URL_HOST));
        $url_host = strtolower((string) parse_url($url, PHP_URL_HOST));
        if ($url === '' || $host === '' || $url_host !== $host) {
            return false;
        }

        $key = self::get_key();
        
        $data = [
            'host' => $host,
            'key' => $key,
            'keyLocation' => home_url($key . '.txt'),
            'urlList' => [$url]
        ];

        return wp_remote_post(self::$api_url, [
            'method' => 'POST',
            'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
            'body' => wp_json_encode($data),
            'timeout' => 10,
            'blocking' => false, // Don't slow down the user.
            'sslverify' => true,
            'reject_unsafe_urls' => true,
        ]);
    }

    /**
     * Ensure the key file exists or is handled via rewrite
     */
    public static function check_key_file()
    {
        if (!self::is_enabled()) {
            return;
        }

        self::get_key();
    }

    /**
     * Serve the IndexNow key file virtually.
     */
    public static function maybe_serve_key_file()
    {
        if (!self::is_enabled()) {
            return;
        }

        $key = self::get_key();
        if ($key === '') {
            return;
        }

        $requested_key = (string) get_query_var('earlystart_indexnow_key_file');
        if ($requested_key === '') {
            $path = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
            $path = parse_url($path, PHP_URL_PATH);
            $requested_key = is_string($path) ? basename($path, '.txt') : '';
        }

        if (!hash_equals($key, $requested_key)) {
            return;
        }

        status_header(200);
        header('Content-Type: text/plain; charset=utf-8');
        header('X-Robots-Tag: noindex');
        echo esc_html($key);
        exit;
    }
}

earlystart_IndexNow::init();


