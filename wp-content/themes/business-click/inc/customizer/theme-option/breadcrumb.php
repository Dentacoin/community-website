<?php
global $business_click_sections;
global $business_click_settings_controls;
global $business_click_repeated_settings_controls;
global $business_click_customizer_defaults;

/*defaults values*/
$business_click_customizer_defaults['business-click-enable-breadcrumb'] = 1;

// section for breadcrumb
$business_click_sections['business-click-breadcrumb-options'] = array(
        'priority'       => 500,
        'title'          => esc_html__( 'Breadcrumb Options', 'business-click' ),
        'panel'          => 'business-click-theme-options',
    );

// enable option for breadcrumb
$business_click_settings_controls['business-click-enable-breadcrumb'] = array(
        'setting' =>     array(
            'default'              => $business_click_customizer_defaults['business-click-enable-breadcrumb'],
        ),
        'control' => array(
            'label'                 =>  esc_html__( 'Enable Breadcrumb', 'business-click' ),
            'section'               => 'business-click-breadcrumb-options',
            'type'                  => 'checkbox',
            'priority'              => 10,
        )
    );