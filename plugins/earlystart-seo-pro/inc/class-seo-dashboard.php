<?php
/**
 * SEO & LLM Dashboard (Skeleton)
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_SEO_Dashboard
{
    public function init()
    {
        add_action('admin_menu', [$this, 'register_menu_page'], 20);
    }

    public function register_menu_page()
    {
        add_menu_page(
            'Early Start SEO',
            'SEO & LLM',
            'manage_options',
            'earlystart-seo-dashboard',
            [$this, 'render_page'],
            'dashicons-chart-area',
            80
        );
    }

    public function render_page()
    {
        echo '<div class="wrap"><h1>Early Start SEO Dashboard</h1><p>Welcome to the Early Start SEO management console.</p></div>';
    }
}
