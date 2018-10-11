<?php
global$business_click_sections;
global$business_click_settings_controls;
global$business_click_customizer_defaults;

/*defaults values*/
$business_click_customizer_defaults['business-click-site-identity-color']                       = '#313131';
$business_click_customizer_defaults['business-click-top-header-background-bar-color']           = '#578ECF';
$business_click_customizer_defaults['business-click-menu-header-background-color']              = '#FFFFFF';
$business_click_customizer_defaults['business-click-business-clcik-h1-h6']                      = '#000000';
$business_click_customizer_defaults['business-click-footer-background-color']                   = '#1F1F1F';
$business_click_customizer_defaults['business-click-color-reset']                               = '';


/*Default color*/
$business_click_sections['colors'] = array(
        'priority'       => 110,
        'title'          => esc_html__( 'Colors', 'business-click' ),
        'panel'          => 'business-click-theme-options'
    );


$business_click_settings_controls['business-click-site-identity-color'] = array(
    'setting' =>  array(
        'default'  => $business_click_customizer_defaults['business-click-site-identity-color'],
    ),
    'control' => array(
        'label'                 =>  esc_html__( 'Site Identity Color', 'business-click' ),
        'description'           =>  esc_html__( 'Site title and tagline color', 'business-click' ),
        'section'               => 'colors',
        'type'                  => 'color',
        'priority'              => 20,
        'active_callback'       => ''
    )
);

$business_click_settings_controls['business-click-top-header-background-bar-color'] = array(
    'setting' => array(
        'default' => $business_click_customizer_defaults['business-click-top-header-background-bar-color'],
    ),
    'control' => array(
        'label'                 =>  esc_html__( 'Top Header Bar Background Color', 'business-click' ),
        'section'               => 'colors',
        'type'                  => 'color',
        'priority'              => 40,
        'active_callback'       => ''
    )
);


$business_click_settings_controls['business-click-menu-header-background-color'] = array(
    'setting' => array(
        'default' => $business_click_customizer_defaults['business-click-menu-header-background-color'],
    ),
    'control' => array(
        'label'                 =>  esc_html__( 'Header Menu Background Color', 'business-click' ),
        'section'               => 'colors',
        'type'                  => 'color',
        'priority'              => 60,
        'active_callback'       => ''
    )
);

$business_click_settings_controls['business-click-business-clcik-h1-h6'] = array(
    'setting' => array(
        'default' => $business_click_customizer_defaults['business-click-business-clcik-h1-h6'],
    ),
    'control' => array(
        'label'                 =>  esc_html__( 'H1-H6 Color', 'business-click' ),
        'section'               => 'colors',
        'type'                  => 'color',
        'priority'              => 150,
        'active_callback'       => ''
    )
);


$business_click_settings_controls['business-click-footer-background-color'] = array(
    'setting' => array(
        'default' => $business_click_customizer_defaults['business-click-footer-background-color'],
    ),
    'control' => array(
        'label'                 =>  esc_html__( 'Footer Background Color', 'business-click' ),
        'section'               => 'colors',
        'type'                  => 'color',
        'priority'              => 160,
        'active_callback'       => ''
    )
);


