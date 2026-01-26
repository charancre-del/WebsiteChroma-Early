<?php
/**
 * ACF Field Group Registrations
 * 
 * @package EarlyStart_Early_Start
 */

if (function_exists('acf_add_local_field_group')):

    acf_add_local_field_group(array(
        'key' => 'group_earlystart_global_settings',
        'title' => 'Global Brand Settings',
        'fields' => array(
            array(
                'key' => 'field_global_phone',
                'label' => 'Global Phone',
                'name' => 'global_phone',
                'type' => 'text',
            ),
            array(
                'key' => 'field_global_email',
                'label' => 'Global Email',
                'name' => 'global_email',
                'type' => 'email',
            ),
            array(
                'key' => 'field_global_address',
                'label' => 'Full Address',
                'name' => 'global_address',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_global_social_links',
                'label' => 'Social Links',
                'name' => 'global_social_links',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_social_platform',
                        'label' => 'Platform',
                        'name' => 'platform',
                        'type' => 'select',
                        'choices' => array(
                            'facebook' => 'Facebook',
                            'instagram' => 'Instagram',
                            'linkedin' => 'LinkedIn',
                        ),
                    ),
                    array(
                        'key' => 'field_social_url',
                        'label' => 'URL',
                        'name' => 'url',
                        'type' => 'url',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'theme-general-settings',
                ),
            ),
        ),
    ));

endif;
