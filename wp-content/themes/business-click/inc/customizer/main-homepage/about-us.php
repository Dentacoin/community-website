<?php
global $business_click_sections;
global $business_click_settings_controls;
global $business_click_repeated_settings_controls;
global $business_click_customizer_defaults;

/*default value*/
$business_click_customizer_defaults['business-click-enable-about-us']   			= 1;
$business_click_customizer_defaults['business-clcik-excerpt-length']				= 30;
$business_click_customizer_defaults['business-click-about-us-select-page']			= 0;
$business_click_customizer_defaults['business-click-about-us-button-text']			= esc_html__('Details','business-click');


/*create section about us*/
$business_click_sections['busiess-click-about-us-section'] = array(
	'title'					=> esc_html__(' About Us Section','business-click'),
	'panel'					=> 'business-click-main-page-options',
	'priority'				=> 70
);

/*enable about us*/
$business_click_settings_controls['business-click-enable-about-us']  =  array(
	'setting' => array(
		'default'			=> $business_click_customizer_defaults['business-click-enable-about-us']
	),
	'control' => array(
		'label'				=> esc_html__('Show About Us Section','business-click'),
		'section'			=> 'busiess-click-about-us-section',
		'type'				=> 'checkbox',
		'priority'			=> 10,
		'active_callback'	=> ''
	)

);

/*excerpt length*/
$business_click_settings_controls['business-clcik-excerpt-length']  =  array(
	'setting' => array(
		'default'			=> $business_click_customizer_defaults['business-clcik-excerpt-length']
	),
	'control' => array(
		'label'				=> esc_html__('Excerpt Length','business-click'),
		'section'			=> 'busiess-click-about-us-section',
		'type'				=> 'number',
		'priority'			=> 20,
		'active_callback'	=> ''
	)

);

/*Select Page*/
$business_click_settings_controls['business-click-about-us-select-page']  =  array(
	'setting' => array(
		'default'			=> $business_click_customizer_defaults['business-click-about-us-select-page']
	),
	'control' => array(
		'label'				=> esc_html__('Select page','business-click'),
		'section'			=> 'busiess-click-about-us-section',
		'type'				=> 'dropdown-pages',
		'priority'			=> 30,
		'active_callback'	=> ''
	)

);

/*button text*/
$business_click_settings_controls['business-click-about-us-button-text']  =  array(
	'setting' => array(
		'default'			=> $business_click_customizer_defaults['business-click-about-us-button-text']
	),
	'control' => array(
		'label'				=> esc_html__('Button Text','business-click'),
		'section'			=> 'busiess-click-about-us-section',
		'type'				=> 'text',
		'priority'			=> 40,
		'active_callback'	=> ''
	)

);

