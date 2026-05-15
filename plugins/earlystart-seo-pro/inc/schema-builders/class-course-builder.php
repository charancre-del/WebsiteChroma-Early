<?php
/**
 * Course Schema Builder
 * Legacy Course builder. Therapy programs are represented as Service/MedicalTherapy schema.
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Course_Builder
{
    /**
     * Output Course Schema
     */
    public static function output()
    {
        if (!is_singular('program')) {
            return;
        }

        return;
    }
}


