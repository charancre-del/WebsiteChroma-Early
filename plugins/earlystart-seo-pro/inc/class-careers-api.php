<?php
/**
 * Careers API Handler
 *
 * Fetches and parses job listings from external sources.
 *
 * @package earlystart_Excellence
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Careers_API
{
    const DEFAULT_FEED_URL = 'https://app.acquire4hire.com/feed/indeed.xml?id=8154';
    const OLD_DEFAULT_FEED_URL = 'https://app.acquire4hire.com/careers/list.json?id=4668';

    /**
     * Migrate the legacy default feed URL without touching custom admin values.
     *
     * @return string Current careers feed URL.
     */
    public static function maybe_migrate_default_feed_url()
    {
        $current = get_option('earlystart_careers_feed_url', null);

        if ($current === null || $current === false || trim((string) $current) === '' || trim((string) $current) === self::OLD_DEFAULT_FEED_URL) {
            update_option('earlystart_careers_feed_url', self::DEFAULT_FEED_URL);
            delete_transient('earlystart_careers_data');
            return self::DEFAULT_FEED_URL;
        }

        return (string) $current;
    }

    /**
     * Get careers data
     *
     * @param bool $force_refresh Whether to bypass cache
     * @return array Array of job data
     */
    public static function get_careers($force_refresh = false)
    {
        // Check for cached data
        $cached_jobs = get_transient('earlystart_careers_data');
        if (false !== $cached_jobs && !$force_refresh) {
            return $cached_jobs;
        }

        // Use option for feed URL to avoid hardcoding in plugin.
        $url = self::maybe_migrate_default_feed_url();
        $safe_url = function_exists('earlystart_seo_validate_remote_url')
            ? earlystart_seo_validate_remote_url($url, true)
            : esc_url_raw($url, array('http', 'https'));

        if (!$safe_url) {
            earlystart_debug_log(' Careers API Error: blocked or invalid feed URL');
            return array();
        }

        // Fetch data with timeout
        $response = wp_remote_get($safe_url, array(
            'timeout' => 15,
            'reject_unsafe_urls' => true,
            'headers' => array(
                'Accept' => 'application/json,text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ),
        ));

        if (is_wp_error($response)) {
            earlystart_debug_log(' Careers API Error: ' . $response->get_error_message());
            return array();
        }

        $body = wp_remote_retrieve_body($response);
        if (empty($body)) {
            return array();
        }

        $jobs = self::parse_json_feed($body, $safe_url);
        if (empty($jobs)) {
            $jobs = self::parse_xml_feed($body, $safe_url);
        }
        if (empty($jobs)) {
            $jobs = self::parse_html_feed($body, $safe_url);
        }

        // Cache for 1 hour
        if (!empty($jobs)) {
            set_transient('earlystart_careers_data', $jobs, HOUR_IN_SECONDS);
        }

        return $jobs;
    }

    /**
     * Parse JSON feed variants from Acquire4Hire or similar providers.
     *
     * @param string $body      Response body.
     * @param string $source_url Feed URL.
     * @return array
     */
    private static function parse_json_feed($body, $source_url)
    {
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            return array();
        }

        $rows = array();
        if (isset($decoded['jobs']) && is_array($decoded['jobs'])) {
            $rows = $decoded['jobs'];
        } elseif (isset($decoded['data']['jobs']) && is_array($decoded['data']['jobs'])) {
            $rows = $decoded['data']['jobs'];
        } elseif (isset($decoded[0]) && is_array($decoded[0])) {
            $rows = $decoded;
        } elseif (isset($decoded['data']) && is_array($decoded['data']) && isset($decoded['data'][0])) {
            $rows = $decoded['data'];
        }

        if (empty($rows)) {
            return array();
        }

        $jobs = array();
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $title = self::first_non_empty($row, array('title', 'job_title', 'jobTitle', 'name', 'position'));
            $url = self::first_non_empty($row, array('url', 'job_url', 'jobUrl', 'apply_url', 'applyUrl', 'link', 'absolute_url'));
            if ($title === '' || $url === '') {
                continue;
            }

            $location = self::first_non_empty($row, array('location', 'job_location', 'city_state', 'city'));
            $state = self::first_non_empty($row, array('state', 'region'));
            if ($location !== '' && $state !== '' && stripos($location, $state) === false) {
                $location .= ', ' . $state;
            }

            $jobs[] = array(
                'title' => sanitize_text_field($title),
                'location' => sanitize_text_field($location),
                'type' => self::normalize_employment_type(self::first_non_empty($row, array('type', 'employment_type', 'employmentType', 'job_type')) ?: 'FULL_TIME'),
                'url' => self::normalize_url($url, $source_url),
                'description' => self::first_non_empty($row, array('description', 'summary', 'excerpt')),
                'date_posted' => self::first_non_empty($row, array('date_posted', 'posted_at', 'postedDate', 'published_at', 'date')),
            );
        }

        return array_values(array_filter($jobs, function ($job) {
            return !empty($job['title']) && !empty($job['url']);
        }));
    }

    /**
     * Parse Indeed XML feed format from Acquire4Hire.
     *
     * @param string $body       Response body.
     * @param string $source_url Feed URL.
     * @return array
     */
    private static function parse_xml_feed($body, $source_url)
    {
        if (!function_exists('simplexml_load_string')) {
            return array();
        }

        $previous_errors = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous_errors);

        if (!$xml) {
            return array();
        }

        $job_nodes = $xml->xpath('//job');
        if (empty($job_nodes)) {
            return array();
        }

        $jobs = array();
        foreach ($job_nodes as $job_node) {
            $title = self::xml_text($job_node, 'title');
            $url = self::xml_text($job_node, 'url');

            if ($title === '' || $url === '') {
                continue;
            }

            $jobs[] = array(
                'title' => sanitize_text_field($title),
                'location' => sanitize_text_field(self::build_location(
                    self::xml_text($job_node, 'city'),
                    self::xml_text($job_node, 'state'),
                    self::xml_text($job_node, 'postalcode')
                )),
                'type' => self::normalize_employment_type(self::xml_text($job_node, 'jobtype')),
                'url' => self::normalize_url($url, $source_url),
                'description' => self::xml_text($job_node, 'description'),
                'date_posted' => self::xml_text($job_node, 'date'),
                'reference_number' => self::xml_text($job_node, 'referencenumber'),
            );
        }

        return array_values(array_filter($jobs, function ($job) {
            return !empty($job['title']) && !empty($job['url']);
        }));
    }

    /**
     * Parse legacy HTML feed format.
     *
     * @param string $body       Response body.
     * @param string $source_url Feed URL.
     * @return array
     */
    private static function parse_html_feed($body, $source_url)
    {
        if (!class_exists('DOMDocument')) {
            return array();
        }

        $jobs = array();
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($body);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $job_nodes = $xpath->query("//div[contains(@class, 'job')]");

        foreach ($job_nodes as $node) {
            $title_node = $xpath->query(".//div[contains(@class, 'job1')]//a//h2", $node)->item(0);
            $link_node = $xpath->query(".//div[contains(@class, 'job1')]//a", $node)->item(0);
            $location_node = $xpath->query(".//div[contains(@class, 'job2')]//div", $node)->item(0);

            if (!$title_node || !$link_node) {
                continue;
            }

            $title = trim($title_node->textContent);
            $job_url = self::normalize_url($link_node->getAttribute('href'), $source_url);
            $location = $location_node ? trim($location_node->textContent) : 'Alpharetta, GA';

            $jobs[] = array(
                'title' => sanitize_text_field($title),
                'location' => sanitize_text_field(trim($location, " \t\n\r\0\x0B,")),
                'type' => 'FULL_TIME',
                'url' => $job_url,
            );
        }

        return array_values(array_filter($jobs, function ($job) {
            return !empty($job['title']) && !empty($job['url']);
        }));
    }

    /**
     * Normalize employment type value to Schema.org supported enums.
     *
     * @param string $raw_type Input type.
     * @return string
     */
    public static function normalize_employment_type($raw_type)
    {
        $type = strtoupper(trim((string) $raw_type));
        $type = str_replace(array('-', '_'), ' ', $type);
        $type = preg_replace('/\s+/', ' ', $type);

        $map = array(
            'FULL TIME' => 'FULL_TIME',
            'FULLTIME' => 'FULL_TIME',
            'PART TIME' => 'PART_TIME',
            'PARTTIME' => 'PART_TIME',
            'CONTRACT' => 'CONTRACTOR',
            'CONTRACTOR' => 'CONTRACTOR',
            'TEMP' => 'TEMPORARY',
            'TEMPORARY' => 'TEMPORARY',
            'INTERN' => 'INTERN',
            'INTERNSHIP' => 'INTERN',
            'VOLUNTEER' => 'VOLUNTEER',
        );

        return isset($map[$type]) ? $map[$type] : 'FULL_TIME';
    }

    /**
     * Return a child node's trimmed text from a SimpleXML element.
     *
     * @param SimpleXMLElement $node Source node.
     * @param string           $key  Child key.
     * @return string
     */
    private static function xml_text($node, $key)
    {
        if (!isset($node->{$key})) {
            return '';
        }

        return trim((string) $node->{$key});
    }

    /**
     * Build a concise display location from XML address parts.
     *
     * @param string $city       City.
     * @param string $state      State.
     * @param string $postalcode Postal code.
     * @return string
     */
    private static function build_location($city, $state, $postalcode)
    {
        $city = trim((string) $city);
        $state = strtoupper(trim((string) $state));
        $postalcode = trim((string) $postalcode);
        $state_zip = trim($state . ' ' . $postalcode);

        if ($city !== '' && $state_zip !== '') {
            return $city . ', ' . $state_zip;
        }

        return $city !== '' ? $city : $state_zip;
    }

    /**
     * Return first non-empty key from an array row.
     *
     * @param array $row  Source row.
     * @param array $keys Candidate keys.
     * @return string
     */
    private static function first_non_empty($row, $keys)
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && !is_array($row[$key])) {
                $value = trim((string) $row[$key]);
                if ($value !== '') {
                    return $value;
                }
            }
        }
        return '';
    }

    /**
     * Normalize feed URL to absolute URL.
     *
     * @param string $value      URL from feed.
     * @param string $source_url Feed endpoint URL.
     * @return string
     */
    private static function normalize_url($value, $source_url)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $value)) {
            return esc_url_raw($value);
        }

        $parts = wp_parse_url($source_url);
        if (empty($parts['scheme']) || empty($parts['host'])) {
            return '';
        }

        $base = $parts['scheme'] . '://' . $parts['host'];
        if (strpos($value, '//') === 0) {
            return esc_url_raw($parts['scheme'] . ':' . $value);
        }

        if (strpos($value, '/') === 0) {
            return esc_url_raw($base . $value);
        }

        return esc_url_raw($base . '/' . ltrim($value, '/'));
    }
}

/**
 * Compatibility wrapper for theme
 */
if (!function_exists('earlystart_get_careers')) {
    function earlystart_get_careers($force_refresh = false) {
        return earlystart_Careers_API::get_careers($force_refresh);
    }
}


