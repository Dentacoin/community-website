<?php
global $business_click_sections;
global $business_click_settings_controls;
global $business_click_repeated_settings_controls;
global $business_click_customizer_defaults;

/*defaults values*/
$business_click_customizer_defaults['business-click-contact-section-enable']					= 1;
$business_click_customizer_defaults['business-click-contact-section-title']						= '';
$business_click_customizer_defaults['business-click-contact-section-contact-form-short-code']	= '';

/*create a section for contct*/
$business_click_sections['business-click-contact-section'] = array(
	'title'							=> esc_html__('Contact us Section','business-click'),
	'panel'							=> 'business-click-main-page-options',
	'priority'						=> 100
);

/*Enable contact section*/
$business_click_settings_controls['business-click-contact-section-enable'] = array(
	'setting' => array(
		'default'					=> $business_click_customizer_defaults['business-click-contact-section-enable']
	),
	'control' => array(
		'label'						=> esc_html__('Show Contact Us Section','business-click'),
		'section'					=> 'business-click-contact-section',
		'type'						=> 'checkbox',
		'priority'					=> 10,
		'active_callback'			=> ''
	)

);

/*section Title */
$business_click_settings_controls['business-click-contact-section-title'] = array(
	'setting' => array(
		'default'					=> $business_click_customizer_defaults['business-click-contact-section-title']
	),
	'control' => array(
		'label'						=> esc_html__('Title Section','business-click'),
		'section'					=> 'business-click-contact-section',
		'type'						=> 'text',
		'priority'					=> 20,
		'active_callback'			=> ''
	)

);

/*contact form short code */
$business_click_settings_controls['business-click-contact-section-contact-form-short-code'] = array(
	'setting' => array(
		'default'					=> $business_click_customizer_defaults['business-click-contact-section-contact-form-short-code']
	),
	'control' => array(
		'label'						=> esc_html__('Shortcode for Contact From 7','business-click'),
		/* translators: %s: contact form plugin */
		'description'       		=> sprintf( '%1$s <a href="https://wordpress.org/plugins/contact-form-7" target="_blank"> %2$s </a> %3$s',  esc_html__( 'Note: To get Contact Form 7', 'business-click' ), esc_html__( 'Click Here', 'business-click' ), esc_html__( '&nbsp', 'business-click' ) ),
		'section'					=> 'business-click-contact-section',
		'type'						=> 'text',
		'priority'					=> 60,
		'active_callback'			=> ''
	)

);