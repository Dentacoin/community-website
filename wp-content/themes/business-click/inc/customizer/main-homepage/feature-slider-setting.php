<?php
global $business_click_sections;
global $business_click_settings_controls;
global $business_click_repeated_settings_controls;
global $business_click_customizer_defaults;

/*defaults value*/
$business_click_customizer_defaults['business-click-enbale-slider']                         = 1;
$business_click_customizer_defaults['business-click-excerpt-length']                        = 30;
$business_click_customizer_defaults['business-click-select-from-page']                      = 0;
$business_click_customizer_defaults['business-click-slider-button-text']                    = esc_html__('Learn more','business-click');

/*create section for feature slider*/
$business_click_sections['business-click-slider-section']  = array(
    'title'                 => esc_html__('Feature Slider Section','business-click'),
    'panel'                 => 'business-click-main-page-options',
    'priority'              => 20
);

/*slider enable */
$business_click_settings_controls['business-click-enbale-slider'] = array(
    'setting' => array(
        'default'          => $business_click_customizer_defaults['business-click-enbale-slider'] 
    ),
    'control' => array(
        'label'             => esc_html__('Show Slider','business-click'),
        'section'           => 'business-click-slider-section',
        'type'              => 'checkbox',
        'priority'          => 10,
        'acticve_callback'  => ''

    )       
);

/*except length */
$business_click_settings_controls['business-click-excerpt-length'] = array(
    'setting' => array(
        'default'          => $business_click_customizer_defaults['business-click-excerpt-length'] 
    ),
    'control' => array(
        'label'             => esc_html__('Excerpt Length','business-click'),
        'section'           => 'business-click-slider-section',
        'type'              => 'number',
        'priority'          => 20,
        'acticve_callback'  => ''

    )       
);

/*post type slider from page */
$business_click_repeated_settings_controls['business-click-select-from-page'] = array(
    'repeated'      => 3,
    'business-click-page-id' => array(
        'setting' => array(
        'default'                   => $business_click_customizer_defaults['business-click-select-from-page'] 
        ),
        'control' => array(
            /* translators: %s: search slider page */
            'label'                 => esc_html__('Slider %s','business-click'),
            'section'               => 'business-click-slider-section',
            'type'                  => 'dropdown-pages',            
            'priority'              => 60,
            'acticve_callback'      => ''

        ),  
    )   
);

/*Button text */
$business_click_settings_controls['business-click-slider-button-text'] = array(
    'setting' => array(
        'default'           => $business_click_customizer_defaults['business-click-slider-button-text'] 
    ),
    'control' => array(
        'label'             => esc_html__('Button text','business-click'),
        'section'           => 'business-click-slider-section',
        'type'              => 'text',
        'priority'          => 70,
        'acticve_callback'  => ''

    )       
);




