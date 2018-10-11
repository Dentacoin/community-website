<?php

global $business_click_sections;
global $business_click_settings_controls;
global $business_click_repeated_settings_controls;
global $business_click_customizer_defaults;

/*defaults value*/
$business_click_customizer_defaults['business-click-blog-section-enable'] 					= 1;
$business_click_customizer_defaults['business-click-blog-section-title-text']				= '';
$business_click_customizer_defaults['business-click-blog-excerpt-length']					= 30;
$business_click_customizer_defaults['business-click-blog-select-category']					=  0;
$business_click_customizer_defaults['business-click-blog-button-text']						= esc_html__('Read More','business-click');


/*create section blog*/
$business_click_sections['business-click-blog-section'] = array(
	'title'							=> esc_html__('Blog Section','business-click'),
	'panel'							=> 'business-click-main-page-options',
	'priority'						=> 90
);

/*enable blog section*/
$business_click_settings_controls['business-click-blog-section-enable']  = array(
	'setting' => array(
		'default'					=> $business_click_customizer_defaults['business-click-blog-section-enable']
	),
	'control' => array(
		'label'						=> esc_html__('Show Blog Section ','business-click'),
		'section'					=> 'business-click-blog-section',
		'type'						=> 'checkbox',
		'priority'					=> 10,
		'active_callback'			=> ''
	)
);

/*Blog section Title*/
$business_click_settings_controls['business-click-blog-section-title-text']  = array(
	'setting' => array(
		'default'					=> $business_click_customizer_defaults['business-click-blog-section-title-text']
	),
	'control' => array(
		'label'						=> esc_html__('Section Title','business-click'),
		'section'					=> 'business-click-blog-section',
		'type'						=> 'text',
		'priority'					=> 20,
		'active_callback'			=> ''
	)
);

/*Excerpt Length*/
$business_click_settings_controls['business-click-blog-excerpt-length']  = array(
	'setting' => array(
		'default'					=> $business_click_customizer_defaults['business-click-blog-excerpt-length']
	),
	'control' => array(
		'label'						=> esc_html__('Excerpt Length','business-click'),
		'section'					=> 'business-click-blog-section',
		'type'						=> 'number',
		'priority'					=> 40,
		'active_callback'			=> ''
	)
);

/*Select Category*/
$business_click_settings_controls['business-click-blog-select-category']  = array(
	'setting' => array(
		'default'					=> $business_click_customizer_defaults['business-click-blog-select-category']
	),
	'control' => array(
		'label'						=> esc_html__('Select Category for Blog','business-click'),
		'section'					=> 'business-click-blog-section',
		'type'						=> 'category_dropdown',
		'priority'					=> 50,
		'active_callback'			=> ''
	)
);


/*Button Text*/
$business_click_settings_controls['business-click-blog-button-text']  = array(
	'setting' => array(
		'default'					=> $business_click_customizer_defaults['business-click-blog-button-text']
	),
	'control' => array(
		'label'						=> esc_html__('Button Text','business-click'),
		'section'					=> 'business-click-blog-section',
		'type'						=> 'text',
		'priority'					=> 60,
		'active_callback'			=> ''
	)
);








