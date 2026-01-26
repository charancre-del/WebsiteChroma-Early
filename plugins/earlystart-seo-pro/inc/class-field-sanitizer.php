<?php
/**
 * Field Sanitizer
 * Sanitization and validation utilities for meta box fields
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Field_Sanitizer
{
    /**
     * Sanitize text field
     */
    public static function sanitize_text($value)
    {
        return sanitize_text_field($value);
    }

    /**
     * Sanitize textarea field
     */
    public static function sanitize_textarea($value)
    {
        return sanitize_textarea_field($value);
    }

    /**
     * Sanitize number field
     */
    public static function sanitize_number($value)
    {
        if ($value === '' || $value === null) {
            return '';
        }
        return is_float($value + 0) ? (float) $value : (int) $value;
    }

    /**
     * Sanitize latitude
     */
    public static function sanitize_latitude($value)
    {
        $value = self::sanitize_number($value);
        if ($value === '') {
            return '';
        }
        $val = (float) $value;
        if ($val < -90 || $val > 90) {
            return '';
        }
        return (string) $value;
    }

    /**
     * Sanitize longitude
     */
    public static function sanitize_longitude($value)
    {
        $value = self::sanitize_number($value);
        if ($value === '') {
            return '';
        }
        $val = (float) $value;
        if ($val < -180 || $val > 180) {
            return '';
        }
        return (string) $value;
    }

    /**
     * Sanitize array of text values
     */
    public static function sanitize_text_array($value)
    {
        if (!is_array($value)) {
            return [];
        }
        return array_map('sanitize_text_field', $value);
    }

    /**
     * Sanitize URL
     */
    public static function sanitize_url($value)
    {
        return esc_url_raw($value);
    }

    /**
     * Sanitize datetime
     */
    public static function sanitize_datetime($value)
    {
        if (empty($value)) {
            return '';
        }
        $timestamp = strtotime($value);
        return ($timestamp === false) ? '' : sanitize_text_field($value);
    }

    /**
     * Sanitize rating value
     */
    public static function sanitize_rating($value)
    {
        $value = self::sanitize_number($value);
        if ($value === '') {
            return '';
        }
        $val = (float) $value;
        if ($val < 0 || $val > 5) {
            return '';
        }
        return (string) $value;
    }

    /**
     * Sanitize price
     */
    public static function sanitize_price($value)
    {
        if ($value === '' || $value === null) {
            return '';
        }
        $value = preg_replace('/[^0-9.]/', '', $value);
        return self::sanitize_number($value);
    }
}
