<?php
/**
 * GoHighLevel (GHL) API Integration
 *
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
    exit;
}

class EarlyStart_GHL_API
{

    private $api_key = 'pit-c99d1046-15e1-4cdd-9189-0200c4b4d5e9';
    private $base_url = 'https://services.leadconnectorhq.com';
    private $version = '2021-07-28';

    public function __construct()
    {
        // Initialization can go here
    }

    /**
     * Helper to make API requests
     */
    private function request($endpoint, $method = 'GET', $body = null)
    {
        $url = $this->base_url . $endpoint;

        $args = array(
            'method' => $method,
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Version' => $this->version,
                'Accept' => 'application/json',
            ),
            'timeout' => 30,
        );

        if ($body !== null) {
            $args['headers']['Content-Type'] = 'application/json';
            $args['body'] = wp_json_encode($body);
        }

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            error_log('GHL API Error: ' . $response->get_error_message());
            return false;
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        if ($code >= 400) {
            error_log('GHL API Error (' . $code . '): ' . $body);
            return false;
        }

        return json_decode($body, true);
    }

    /**
     * Helper to get location ID associated with this API key
     */
    private function get_location_id()
    {
        $response = $this->request('/locations/search');
        if ($response && !empty($response['locations'])) {
            return $response['locations'][0]['id'];
        }
        // Fallback or handle multi-location if necessary.
        // Assuming single location sub-account for this key.
        return null;
    }

    /**
     * Audit and programmatically generate all required Custom Fields
     */
    public function audit_and_generate_custom_fields()
    {
        $location_id = $this->get_location_id();
        if (!$location_id)
            return;

        $existing_fields_response = $this->request('/locations/' . $location_id . '/customFields');
        $existing_fields = array();

        if ($existing_fields_response && isset($existing_fields_response['customFields'])) {
            foreach ($existing_fields_response['customFields'] as $field) {
                $existing_fields[$field['name']] = $field['id'];
            }
        }

        $required_fields = array(
            array('name' => 'Child Date of Birth', 'dataType' => 'DATE'),
            array('name' => 'Primary Diagnosis', 'dataType' => 'TEXT'),
            array('name' => 'Current Therapies', 'dataType' => 'MULTIPLE_OPTIONS', 'options' => array('ABA', 'Speech', 'Occupational Therapy', 'None')),
            array('name' => 'Insurance Provider', 'dataType' => 'TEXT')
        );

        foreach ($required_fields as $rf) {
            if (!isset($existing_fields[$rf['name']])) {
                $payload = array(
                    'name' => $rf['name'],
                    'dataType' => $rf['dataType'],
                );

                if (isset($rf['options'])) {
                    $payload['options'] = $rf['options'];
                }

                $this->request('/locations/' . $location_id . '/customFields', 'POST', $payload);
            }
        }
    }

    /**
     * Create or Update a Contact in GHL
     */
    public function upsert_contact($data)
    {
        $location_id = $this->get_location_id();
        if (!$location_id)
            return false;

        $payload = array(
            'locationId' => $location_id,
        );

        // Map common fields
        $fields_map = array('firstName', 'lastName', 'name', 'email', 'phone', 'address1', 'city', 'state', 'postalCode', 'tags', 'customFields');
        foreach ($fields_map as $field) {
            if (isset($data[$field])) {
                $payload[$field] = $data[$field];
            }
        }

        return $this->request('/contacts/upsert', 'POST', $payload);
    }

    /**
     * Create an Opportunity in GHL
     */
    public function create_opportunity($contact_id, $pipeline_id, $stage_id, $title)
    {
        $location_id = $this->get_location_id();
        if (!$location_id)
            return false;

        $payload = array(
            'pipelineId' => $pipeline_id,
            'locationId' => $location_id,
            'name' => $title,
            'pipelineStageId' => $stage_id,
            'status' => 'open',
            'contactId' => $contact_id
        );

        return $this->request('/opportunities/', 'POST', $payload);
    }

    /**
     * Dynamic Task Routing
     */
    public function create_task($contact_id, $title, $description, $due_date, $assigned_to = null)
    {
        $payload = array(
            'title' => $title,
            'body' => $description,
            'dueDate' => $due_date,
            'completed' => false
        );

        if ($assigned_to) {
            $payload['assignedTo'] = $assigned_to;
        }

        return $this->request('/contacts/' . $contact_id . '/tasks', 'POST', $payload);
    }

    /**
     * Contextual Notes
     */
    public function add_note($contact_id, $body)
    {
        $payload = array(
            'body' => $body
        );

        return $this->request('/contacts/' . $contact_id . '/notes', 'POST', $payload);
    }
}

// Instantiate globally or via hook
function earlystart_ghl_init()
{
    global $earlystart_ghl;
    $earlystart_ghl = new EarlyStart_GHL_API();
}
add_action('init', 'earlystart_ghl_init');
