<?php
/**
 * LLM Bulk Processor
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

    public function __construct()
    {
        add_action(self::CRON_HOOK, [$this, 'process_next_item']);
    }

    public function process_next_item()
    {
        $queue = get_option(self::QUEUE_OPTION, []);
        if (empty($queue))
            return;

        $next_index = null;
        foreach ($queue as $index => $item) {
            if ($item['status'] === 'pending') {
                $next_index = $index;
                break;
            }
        }

        if ($next_index === null)
            return;

        $item = $queue[$next_index];
        $queue[$next_index]['status'] = 'processing';
        update_option(self::QUEUE_OPTION, $queue);

        // Processing logic...
        // earlystart_LLM_Client instance would be used here.
    }
}
new earlystart_LLM_Bulk_Processor();
