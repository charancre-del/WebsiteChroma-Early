<?php
/**
 * Contact Page Meta Boxes
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Contact Page Meta Boxes
 */
function earlystart_contact_page_meta_boxes() {
	add_meta_box(
		'chroma-contact-hero',
		__( 'Hero Section', 'earlystart-early-learning' ),
		'earlystart_contact_hero_meta_box_render',
		'page',
		'normal',
		'high'
	);

	add_meta_box(
		'chroma-contact-form',
		__( 'Form Settings', 'earlystart-early-learning' ),
		'earlystart_contact_form_meta_box_render',
		'page',
		'normal',
		'default'
	);

	add_meta_box(
		'chroma-contact-routing',
		__( 'Routing Cards', 'earlystart-early-learning' ),
		'earlystart_contact_routing_meta_box_render',
		'page',
		'normal',
		'default'
	);

	add_meta_box(
		'chroma-contact-corporate',
		__( 'Corporate Office Info', 'earlystart-early-learning' ),
		'earlystart_contact_corporate_meta_box_render',
		'page',
		'normal',
		'default'
	);

	add_meta_box(
		'chroma-contact-careers',
		__( 'Careers Section', 'earlystart-early-learning' ),
		'earlystart_contact_careers_meta_box_render',
		'page',
		'normal',
		'default'
	);

	add_meta_box(
		'chroma-contact-press',
		__( 'Press Inquiries Section', 'earlystart-early-learning' ),
		'earlystart_contact_press_meta_box_render',
		'page',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'earlystart_contact_page_meta_boxes' );

/**
 * Hero Section Meta Box
 */
function earlystart_contact_hero_meta_box_render( $post ) {
	wp_nonce_field( 'earlystart_contact_hero_meta', 'earlystart_contact_hero_nonce' );

	$hero_badge       = get_post_meta( $post->ID, 'contact_hero_badge', true );
	$hero_title       = get_post_meta( $post->ID, 'contact_hero_title', true );
	$hero_description = get_post_meta( $post->ID, 'contact_hero_description', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="contact_hero_badge">Badge Text</label></th>
			<td>
				<input type="text" id="contact_hero_badge" name="contact_hero_badge"
					   value="<?php echo esc_attr( $hero_badge ); ?>"
					   class="large-text" placeholder="e.g., Start Your Journey" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_hero_title">Title</label></th>
			<td>
				<input type="text" id="contact_hero_title" name="contact_hero_title"
					   value="<?php echo esc_attr( $hero_title ); ?>"
					   class="large-text" placeholder="e.g., We'd love to meet you." />
			</td>
		</tr>
		<tr>
			<th><label for="contact_hero_description">Description</label></th>
			<td>
				<textarea id="contact_hero_description" name="contact_hero_description"
						  rows="3" class="large-text"><?php echo esc_textarea( $hero_description ); ?></textarea>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Form Settings Meta Box
 */
function earlystart_contact_form_meta_box_render( $post ) {
	wp_nonce_field( 'earlystart_contact_form_meta', 'earlystart_contact_form_nonce' );

	$form_submit_text = get_post_meta( $post->ID, 'contact_form_submit_text', true );
	$form_heading     = get_post_meta( $post->ID, 'contact_form_heading', true );
	$form_intro       = get_post_meta( $post->ID, 'contact_form_intro', true );
	$form_card_title  = get_post_meta( $post->ID, 'contact_form_card_title', true );
	$fallback_intro   = get_post_meta( $post->ID, 'contact_form_fallback_intro', true );
	$fallback_call_label = get_post_meta( $post->ID, 'contact_form_fallback_call_label', true );
	$fallback_email_label = get_post_meta( $post->ID, 'contact_form_fallback_email_label', true );
	$department_heading = get_post_meta( $post->ID, 'contact_department_heading', true );
	$locations_heading_plural = get_post_meta( $post->ID, 'contact_locations_heading_plural', true );
	$locations_heading_singular = get_post_meta( $post->ID, 'contact_locations_heading_singular', true );
	$locations_heading_empty = get_post_meta( $post->ID, 'contact_locations_heading_empty', true );
	$locations_description = get_post_meta( $post->ID, 'contact_locations_description', true );
	$locations_link_text = get_post_meta( $post->ID, 'contact_locations_link_text', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="contact_form_heading">Form Section Heading</label></th>
			<td>
				<input type="text" id="contact_form_heading" name="contact_form_heading"
					   value="<?php echo esc_attr( $form_heading ); ?>"
					   class="large-text" placeholder="e.g., Get Started Today" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_form_intro">Form Section Intro</label></th>
			<td>
				<textarea id="contact_form_intro" name="contact_form_intro"
						  rows="3" class="large-text"><?php echo esc_textarea( $form_intro ); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><label for="contact_form_card_title">Form Card Title</label></th>
			<td>
				<input type="text" id="contact_form_card_title" name="contact_form_card_title"
					   value="<?php echo esc_attr( $form_card_title ); ?>"
					   class="large-text" placeholder="e.g., Send a Message" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_form_submit_text">Submit Button Text</label></th>
			<td>
				<input type="text" id="contact_form_submit_text" name="contact_form_submit_text"
					   value="<?php echo esc_attr( $form_submit_text ); ?>"
					   class="large-text" placeholder="e.g., Submit Request" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_form_fallback_intro">Fallback Form Intro</label></th>
			<td>
				<textarea id="contact_form_fallback_intro" name="contact_form_fallback_intro"
						  rows="3" class="large-text"><?php echo esc_textarea( $fallback_intro ); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><label for="contact_form_fallback_call_label">Fallback Call Label</label></th>
			<td>
				<input type="text" id="contact_form_fallback_call_label" name="contact_form_fallback_call_label"
					   value="<?php echo esc_attr( $fallback_call_label ); ?>"
					   class="large-text" placeholder="e.g., Call" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_form_fallback_email_label">Fallback Email Label</label></th>
			<td>
				<input type="text" id="contact_form_fallback_email_label" name="contact_form_fallback_email_label"
					   value="<?php echo esc_attr( $fallback_email_label ); ?>"
					   class="large-text" placeholder="e.g., Email" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_department_heading">Department Heading</label></th>
			<td>
				<input type="text" id="contact_department_heading" name="contact_department_heading"
					   value="<?php echo esc_attr( $department_heading ); ?>"
					   class="large-text" placeholder="e.g., Departmental Emails" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_locations_heading_plural">Locations Heading (Plural)</label></th>
			<td>
				<input type="text" id="contact_locations_heading_plural" name="contact_locations_heading_plural"
					   value="<?php echo esc_attr( $locations_heading_plural ); ?>"
					   class="large-text" placeholder="e.g., Visit one of our %s clinics." />
				<p class="description">Use %s where the clinic count should appear.</p>
			</td>
		</tr>
		<tr>
			<th><label for="contact_locations_heading_singular">Locations Heading (Singular)</label></th>
			<td>
				<input type="text" id="contact_locations_heading_singular" name="contact_locations_heading_singular"
					   value="<?php echo esc_attr( $locations_heading_singular ); ?>"
					   class="large-text" placeholder="e.g., Visit our clinic." />
			</td>
		</tr>
		<tr>
			<th><label for="contact_locations_heading_empty">Locations Heading (No Count)</label></th>
			<td>
				<input type="text" id="contact_locations_heading_empty" name="contact_locations_heading_empty"
					   value="<?php echo esc_attr( $locations_heading_empty ); ?>"
					   class="large-text" placeholder="e.g., Visit one of our clinics." />
			</td>
		</tr>
		<tr>
			<th><label for="contact_locations_description">Locations Description</label></th>
			<td>
				<textarea id="contact_locations_description" name="contact_locations_description"
						  rows="3" class="large-text"><?php echo esc_textarea( $locations_description ); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><label for="contact_locations_link_text">Locations Link Text</label></th>
			<td>
				<input type="text" id="contact_locations_link_text" name="contact_locations_link_text"
					   value="<?php echo esc_attr( $locations_link_text ); ?>"
					   class="large-text" placeholder="e.g., View Location Directory" />
			</td>
		</tr>
	</table>
	<p class="description">Form functionality can be configured with a contact form plugin (Contact Form 7, Gravity Forms, etc.)</p>
	<?php
}

/**
 * Routing Cards Meta Box
 */
function earlystart_contact_routing_meta_box_render( $post ) {
	wp_nonce_field( 'earlystart_contact_routing_meta', 'earlystart_contact_routing_nonce' );

	$routes = get_post_meta( $post->ID, 'contact_routes_json', true );
	if ( is_array( $routes ) ) {
		$routes = wp_json_encode( $routes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	}
	?>
	<table class="form-table">
		<tr>
			<th><label for="contact_routes_json">Routing Cards JSON</label></th>
			<td>
				<textarea id="contact_routes_json" name="contact_routes_json"
						  rows="12" class="large-text code"><?php echo esc_textarea( $routes ); ?></textarea>
				<p class="description">Each item supports icon, title, desc, link, label, and color (rose, orange, or amber).</p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Corporate Office Meta Box
 */
function earlystart_contact_corporate_meta_box_render( $post ) {
	wp_nonce_field( 'earlystart_contact_corporate_meta', 'earlystart_contact_corporate_nonce' );

	$corporate_title   = get_post_meta( $post->ID, 'contact_corporate_title', true );
	$corporate_name    = get_post_meta( $post->ID, 'contact_corporate_name', true );
	$corporate_address = get_post_meta( $post->ID, 'contact_corporate_address', true );
	$corporate_phone   = get_post_meta( $post->ID, 'contact_corporate_phone', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="contact_corporate_title">Section Title</label></th>
			<td>
				<input type="text" id="contact_corporate_title" name="contact_corporate_title"
					   value="<?php echo esc_attr( $corporate_title ); ?>"
					   class="large-text" placeholder="e.g., Corporate Office" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_corporate_name">Office Name</label></th>
			<td>
				<input type="text" id="contact_corporate_name" name="contact_corporate_name"
					   value="<?php echo esc_attr( $corporate_name ); ?>"
					   class="large-text" placeholder="e.g., Chroma Early Start HQ" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_corporate_address">Address</label></th>
			<td>
				<textarea id="contact_corporate_address" name="contact_corporate_address"
						  rows="3" class="large-text"
						  placeholder="3554 Old Milton Pkwy&#10;Alpharetta, GA 30005"><?php echo esc_textarea( $corporate_address ); ?></textarea>
				<p class="description">Enter each line of the address on a new line</p>
			</td>
		</tr>
		<tr>
			<th><label for="contact_corporate_phone">Phone Number</label></th>
			<td>
				<input type="text" id="contact_corporate_phone" name="contact_corporate_phone"
					   value="<?php echo esc_attr( $corporate_phone ); ?>"
					   placeholder="e.g., (404) 905-6775" />
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Careers Section Meta Box
 */
function earlystart_contact_careers_meta_box_render( $post ) {
	wp_nonce_field( 'earlystart_contact_careers_meta', 'earlystart_contact_careers_nonce' );

	$careers_title       = get_post_meta( $post->ID, 'contact_careers_title', true );
	$careers_description = get_post_meta( $post->ID, 'contact_careers_description', true );
	$careers_link_text   = get_post_meta( $post->ID, 'contact_careers_link_text', true );
	$careers_link_url    = get_post_meta( $post->ID, 'contact_careers_link_url', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="contact_careers_title">Section Title</label></th>
			<td>
				<input type="text" id="contact_careers_title" name="contact_careers_title"
					   value="<?php echo esc_attr( $careers_title ); ?>"
					   class="large-text" placeholder="e.g., Careers" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_careers_description">Description</label></th>
			<td>
				<textarea id="contact_careers_description" name="contact_careers_description"
						  rows="2" class="large-text"><?php echo esc_textarea( $careers_description ); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><label for="contact_careers_link_text">Link Text</label></th>
			<td>
				<input type="text" id="contact_careers_link_text" name="contact_careers_link_text"
					   value="<?php echo esc_attr( $careers_link_text ); ?>"
					   class="large-text" placeholder="e.g., View Open Positions" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_careers_link_url">Link URL</label></th>
			<td>
				<input type="url" id="contact_careers_link_url" name="contact_careers_link_url"
					   value="<?php echo esc_attr( $careers_link_url ); ?>"
					   class="large-text" placeholder="e.g., /careers" />
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Press Inquiries Section Meta Box
 */
function earlystart_contact_press_meta_box_render( $post ) {
	wp_nonce_field( 'earlystart_contact_press_meta', 'earlystart_contact_press_nonce' );

	$press_title       = get_post_meta( $post->ID, 'contact_press_title', true );
	$press_description = get_post_meta( $post->ID, 'contact_press_description', true );
	$press_link_text   = get_post_meta( $post->ID, 'contact_press_link_text', true );
	$press_link_url    = get_post_meta( $post->ID, 'contact_press_link_url', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="contact_press_title">Section Title</label></th>
			<td>
				<input type="text" id="contact_press_title" name="contact_press_title"
					   value="<?php echo esc_attr( $press_title ); ?>"
					   class="large-text" placeholder="e.g., Press Inquiries" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_press_description">Description</label></th>
			<td>
				<textarea id="contact_press_description" name="contact_press_description"
						  rows="2" class="large-text"><?php echo esc_textarea( $press_description ); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><label for="contact_press_link_text">Link Text</label></th>
			<td>
				<input type="text" id="contact_press_link_text" name="contact_press_link_text"
					   value="<?php echo esc_attr( $press_link_text ); ?>"
					   class="large-text" placeholder="e.g., Visit Newsroom" />
			</td>
		</tr>
		<tr>
			<th><label for="contact_press_link_url">Link URL</label></th>
			<td>
				<input type="url" id="contact_press_link_url" name="contact_press_link_url"
					   value="<?php echo esc_attr( $press_link_url ); ?>"
					   class="large-text" placeholder="e.g., /newsroom" />
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Save Contact Page Meta
 */
function earlystart_save_contact_page_meta( $post_id ) {
	if ( get_post_type( $post_id ) !== 'page' ) {
		return;
	}

	// Define all meta fields
	$meta_boxes = array(
		'earlystart_contact_hero_nonce' => array(
			'contact_hero_badge'       => 'sanitize_text_field',
			'contact_hero_title'       => 'sanitize_text_field',
			'contact_hero_description' => 'sanitize_textarea_field',
		),
		'earlystart_contact_form_nonce' => array(
			'contact_form_heading'             => 'sanitize_text_field',
			'contact_form_intro'               => 'sanitize_textarea_field',
			'contact_form_card_title'          => 'sanitize_text_field',
			'contact_form_submit_text'         => 'sanitize_text_field',
			'contact_form_fallback_intro'      => 'sanitize_textarea_field',
			'contact_form_fallback_call_label' => 'sanitize_text_field',
			'contact_form_fallback_email_label' => 'sanitize_text_field',
			'contact_department_heading'       => 'sanitize_text_field',
			'contact_locations_heading_plural' => 'sanitize_text_field',
			'contact_locations_heading_singular' => 'sanitize_text_field',
			'contact_locations_heading_empty'  => 'sanitize_text_field',
			'contact_locations_description'    => 'sanitize_textarea_field',
			'contact_locations_link_text'      => 'sanitize_text_field',
		),
		'earlystart_contact_routing_nonce' => array(
			'contact_routes_json' => 'earlystart_contact_sanitize_routes_json',
		),
		'earlystart_contact_corporate_nonce' => array(
			'contact_corporate_title'   => 'sanitize_text_field',
			'contact_corporate_name'    => 'sanitize_text_field',
			'contact_corporate_address' => 'sanitize_textarea_field',
			'contact_corporate_phone'   => 'sanitize_text_field',
		),
		'earlystart_contact_careers_nonce' => array(
			'contact_careers_title'       => 'sanitize_text_field',
			'contact_careers_description' => 'sanitize_textarea_field',
			'contact_careers_link_text'   => 'sanitize_text_field',
			'contact_careers_link_url'    => 'esc_url_raw',
		),
		'earlystart_contact_press_nonce' => array(
			'contact_press_title'       => 'sanitize_text_field',
			'contact_press_description' => 'sanitize_textarea_field',
			'contact_press_link_text'   => 'sanitize_text_field',
			'contact_press_link_url'    => 'esc_url_raw',
		),
	);

	// Process each meta box
	foreach ( $meta_boxes as $nonce_field => $fields ) {
		if ( ! isset( $_POST[ $nonce_field ] ) ) {
			continue;
		}

		$nonce_action = str_replace( '_nonce', '_meta', $nonce_field );
		if ( ! wp_verify_nonce( $_POST[ $nonce_field ], $nonce_action ) ) {
			continue;
		}

		// Save each field
		foreach ( $fields as $field_name => $sanitize_function ) {
			if ( isset( $_POST[ $field_name ] ) ) {
				$value = call_user_func( $sanitize_function, $_POST[ $field_name ] );
				update_post_meta( $post_id, $field_name, $value );
			}
		}
	}
}
add_action( 'save_post', 'earlystart_save_contact_page_meta' );

/**
 * Sanitize contact routing card JSON.
 */
function earlystart_contact_sanitize_routes_json( $value ) {
	$decoded = is_array( $value ) ? $value : json_decode( wp_unslash( (string) $value ), true );
	if ( ! is_array( $decoded ) ) {
		return '';
	}

	$allowed_colors = array( 'rose', 'orange', 'amber' );
	$routes         = array();

	foreach ( $decoded as $route ) {
		if ( ! is_array( $route ) ) {
			continue;
		}

		$color = sanitize_key( $route['color'] ?? 'rose' );
		if ( ! in_array( $color, $allowed_colors, true ) ) {
			$color = 'rose';
		}

		$routes[] = array(
			'icon'  => sanitize_key( $route['icon'] ?? 'circle' ),
			'title' => sanitize_text_field( $route['title'] ?? '' ),
			'desc'  => sanitize_textarea_field( $route['desc'] ?? '' ),
			'link'  => esc_url_raw( $route['link'] ?? '' ),
			'label' => sanitize_text_field( $route['label'] ?? '' ),
			'color' => $color,
		);
	}

	return wp_json_encode( $routes, JSON_UNESCAPED_SLASHES );
}

/**
 * Seed default values for Contact page
 */
function earlystart_seed_contact_page_defaults( $post_id ) {
	if ( get_post_type( $post_id ) !== 'page' ) {
		return;
	}

	$template = get_post_meta( $post_id, '_wp_page_template', true );
	if ( $template !== 'page-contact.php' ) {
		return;
	}

	$already_seeded = get_post_meta( $post_id, '_contact_defaults_seeded', true );
	if ( $already_seeded ) {
		return;
	}

	$defaults = array(
		'contact_hero_badge'       => 'Start Your Journey',
		'contact_hero_title'       => 'We\'d love to meet you.',
		'contact_hero_description' => 'Ready to experience the Early Start difference? Schedule a tour or ask us a question below to get started.',

		'contact_form_heading'        => 'Get Started Today',
		'contact_form_intro'          => 'Ready to learn more? Fill out the form, and our admissions team will reach out within 24 hours to guide you through the process.',
		'contact_form_card_title'     => 'Send a Message',
		'contact_form_submit_text'    => 'Submit Request',
		'contact_form_fallback_intro' => 'Our admissions team can help with program questions, referrals, tours, and next steps for your family.',
		'contact_form_fallback_call_label' => 'Call',
		'contact_form_fallback_email_label' => 'Email',
		'contact_department_heading'  => 'Departmental Emails',
		'contact_locations_heading_plural' => 'Visit one of our %s clinics.',
		'contact_locations_heading_singular' => 'Visit our clinic.',
		'contact_locations_heading_empty' => 'Visit one of our clinics.',
		'contact_locations_description' => 'With specialized therapy centers across the region, there is likely a Chroma Early Start clinic in your community.',
		'contact_locations_link_text' => 'View Location Directory',
		'contact_routes_json'      => wp_json_encode(
			array(
				array(
					'icon'  => 'baby',
					'title' => 'For Families',
					'desc'  => 'Find a clinic near you and schedule a tour for ABA, Speech, or OT.',
					'link'  => '/locations',
					'label' => 'Find a Clinic',
					'color' => 'rose',
				),
				array(
					'icon'  => 'briefcase',
					'title' => 'For Clinicians',
					'desc'  => 'View our open positions and learn about our culture of burnout prevention.',
					'link'  => '/careers',
					'label' => 'View Careers',
					'color' => 'orange',
				),
				array(
					'icon'  => 'heart-pulse',
					'title' => 'For Providers',
					'desc'  => 'Easily refer a client to our clinical team for a comprehensive assessment.',
					'link'  => '/contact#general-form',
					'label' => 'Refer a Client',
					'color' => 'amber',
				),
			),
			JSON_UNESCAPED_SLASHES
		),

		'contact_corporate_title'   => 'Corporate Office',
		'contact_corporate_name'    => 'Chroma Early Start HQ',
		'contact_corporate_address' => "3554 Old Milton Pkwy\nAlpharetta, GA 30005",
		'contact_corporate_phone'   => '(404) 905-6775',

		'contact_careers_title'       => 'Careers',
		'contact_careers_description' => 'Passionate about pediatric therapy and family support? We are always looking for dedicated clinicians, care coordinators, and operational leaders.',
		'contact_careers_link_text'   => 'View Open Positions',
		'contact_careers_link_url'    => '/careers',

		'contact_press_title'       => 'Press Inquiries',
		'contact_press_description' => 'For media kits and interview requests with our leadership team.',
		'contact_press_link_text'   => 'Visit Newsroom',
		'contact_press_link_url'    => '/newsroom',
	);

	foreach ( $defaults as $meta_key => $default_value ) {
		update_post_meta( $post_id, $meta_key, $default_value );
	}

	update_post_meta( $post_id, '_contact_defaults_seeded', '1' );
}
add_action( 'save_post', 'earlystart_seed_contact_page_defaults', 5 );


