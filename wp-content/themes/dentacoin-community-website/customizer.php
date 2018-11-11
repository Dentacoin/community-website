<?php

function wpv_customize_register($wp_customize) {

    /*
    // Abschnitt (Section)
    $wp_customize->add_section('wpv_abschnitt1', array(
		'title'       => 'Neuer Abschnitt',
		'priority'    => 1,
		'description' => 'Beschreibung',
	));

    // Einstellungen (Settings)
    $wp_customize->add_setting('wpv_setting1', array(
        'default' => 'Platzhalter',
        'sanitize_callback' => 'sanitize_text_field', // sichere Textausgabe
        'type' => 'option', // alternativ theme_mod
    ));

    // Bedienfelder (Controls)
    $wp_customize->add_control('wpv_control1', array(
        'label' => 'Beschriftung',
        'section' => 'wpv_abschnitt1',
        'settings' => 'wpv_setting1',
        'type' => 'text',
    ));
    */


    // Abschnitt (Section)
    $wp_customize->add_section('wpv_section', array(
		'title'       => 'Customized options: font color & blendmode ',
		'priority'    => 1,
		'description' => '',
	));

    // Einstellungen (Settings) für BlendModes
	$wp_customize->add_setting('wpv_blendmodes', array(
		'default'           => 'normal',
		'sanitize_callback' => 'sanitize_text_field',
		'type'              => 'option',
	));


    // Einstellungen (Settings) für Schriftfarbe
	$wp_customize->add_setting('wpv_color', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_text_field',
		'type'              => 'option',
	));

    // Bedienfelder (Controls) für BlendModes
	$wp_customize->add_control('wpv_blendmode_control', array(
		'label'   => 'Select blendmode',
		'section' => 'wpv_section',
		'settings' => 'wpv_blendmodes',
		'type'    => 'select',
		'choices' => array(
			'normal' => 'Normal',
			'multiply' => 'Multiply',
      'difference' => 'Difference',
			'soft-light' => 'Soft light',
		),
	));

	// Bedienfelder (Controls) für Schriftfarbe
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'wpv_color_control',
			array(
				'label'    => 'Select font color',
				'section'  => 'wpv_section',
				'settings' => 'wpv_color',
			)
		)
	);



}

add_action('customize_register', 'wpv_customize_register');

?>
