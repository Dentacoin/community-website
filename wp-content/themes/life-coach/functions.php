<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 *	After theme Setup Hook
 */
function life_coach_theme_setup() {
	/**
	* Make child theme available for translation.
    * Translations can be filed in the /languages/ directory.
	*/
	load_child_theme_textdomain( 'life-coach', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'life_coach_theme_setup' );

/**
 * Enqueue scripts and styles.
 */
if( ! function_exists( 'life_coach_scripts' ) ):
	function life_coach_scripts() {
		$my_theme = wp_get_theme();
    	$version = $my_theme['Version'];

    	 wp_enqueue_style( 'blossom-coach',  get_template_directory_uri()  . '/style.css', array( 'animate' ) );

		wp_enqueue_style( 'life-coach', get_stylesheet_directory_uri(). '/style.css' , array( 'blossom-coach', 'blossom-coach-woocommerce' ) , $version );
	}
endif;
add_action( 'wp_enqueue_scripts', 'life_coach_scripts' );

//Remove a function from the parent theme
function life_coach_remove_parent_filters(){ //Have to do it after theme setup, because child theme functions are loaded first
    remove_action( 'customize_register', 'blossom_coach_customizer_theme_info' );
}
add_action( 'init', 'life_coach_remove_parent_filters' );

function life_coach_customize_register( $wp_customize ){

	/* THEME INFO */
	$wp_customize->add_section( 'theme_info', array(
		'title'       => __( 'Demo & Documentation' , 'life-coach' ),
		'priority'    => 6,
	) );
    
    /** Important Links */
	$wp_customize->add_setting( 'theme_info_theme',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $theme_info = '<p>';
	$theme_info .= sprintf( __( 'Demo Link: %1$sClick here.%2$s', 'life-coach' ),  '<a href="' . esc_url( 'https://demo.blossomthemes.com/life-coach' ) . '" target="_blank">', '</a>' );
    $theme_info .= '</p><p>';
    $theme_info .= sprintf( __( 'Documentation Link: %1$sClick here.%2$s', 'life-coach' ),  '<a href="' . esc_url( 'https://blossomthemes.com/life-coach-free-theme-documentation/' ) . '" target="_blank">', '</a>' );
    $theme_info .= '</p>';

	$wp_customize->add_control( new Blossom_Coach_Note_Control( $wp_customize,
        'theme_info_theme', 
            array(
                'section'     => 'theme_info',
                'description' => $theme_info
            )
        )
    );

    /** Site Title Font */
    $wp_customize->add_setting( 
        'site_title_font', 
        array(
            'default' => array(                                         
                'font-family' => 'Montserrat',
                'variant'     => '700',
            ),
            'sanitize_callback' => array( 'Blossom_Coach_Fonts', 'sanitize_typography' )
        ) 
    );

    $wp_customize->add_control( 
        new Blossom_Coach_Typography_Control( 
            $wp_customize, 
            'site_title_font', 
            array(
                'label'       => __( 'Site Title Font', 'life-coach' ),
                'description' => __( 'Site title and tagline font.', 'life-coach' ),
                'section'     => 'title_tagline',
                'priority'    => 60, 
            ) 
        ) 
    );
    
    /** Site Title Font Size*/
    $wp_customize->add_setting( 
        'site_title_font_size', 
        array(
            'default'           => 40,
            'sanitize_callback' => 'blossom_coach_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Coach_Slider_Control( 
            $wp_customize,
            'site_title_font_size',
            array(
                'section'     => 'title_tagline',
                'label'       => __( 'Site Title Font Size', 'life-coach' ),
                'description' => __( 'Change the font size of your site title.', 'life-coach' ),
                'priority'    => 65,
                'choices'     => array(
                    'min'   => 10,
                    'max'   => 200,
                    'step'  => 1,
                )                 
            )
        )
    );

    /** Typography */
    $wp_customize->add_section(
        'typography_settings',
        array(
            'title'    => __( 'Typography', 'life-coach' ),
            'priority' => 20,
            'panel'    => 'appearance_settings',
        )
    );
    
    /** Primary Font */
    $wp_customize->add_setting(
		'primary_font',
		array(
			'default'			=> 'Montserrat',
			'sanitize_callback' => 'blossom_coach_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Coach_Select_Control(
    		$wp_customize,
    		'primary_font',
    		array(
                'label'	      => __( 'Primary Font', 'life-coach' ),
                'description' => __( 'Primary font of the site.', 'life-coach' ),
    			'section'     => 'typography_settings',
    			'choices'     => blossom_coach_get_all_fonts(),	
     		)
		)
	);
    
    /** Secondary Font */
    $wp_customize->add_setting(
		'secondary_font',
		array(
			'default'			=> 'Open Sans',
			'sanitize_callback' => 'blossom_coach_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Coach_Select_Control(
    		$wp_customize,
    		'secondary_font',
    		array(
                'label'	      => __( 'Secondary Font', 'life-coach' ),
                'description' => __( 'Secondary font of the site.', 'life-coach' ),
    			'section'     => 'typography_settings',
    			'choices'     => blossom_coach_get_all_fonts(),	
     		)
		)
	);
}
add_action( 'customize_register', 'life_coach_customize_register', 40 );

// Add Customize Script
function life_coach_customize_script(){
    wp_enqueue_script( 'life-coach-customize', get_stylesheet_directory_uri() . '/js/customize.js', array( 'jquery', 'customize-controls', 'blossom-coach-customize' ), '', true );
}
add_action( 'customize_controls_enqueue_scripts', 'life_coach_customize_script', 20 );


function blossom_coach_get_home_sections(){
    $ed_banner = get_theme_mod( 'ed_banner_section', 'slider_banner' );
    $sections = array( 
        'client'      => array( 'sidebar' => 'client' ),
        'about'       => array( 'sidebar' => 'about' ),
        'service'     => array( 'sidebar' => 'service' ),
        'cta'         => array( 'sidebar' => 'cta' ),
        'testimonial' => array( 'sidebar' => 'testimonial' ),       
        'blog'        => array( 'section' => 'blog' ),
        'simple-cta'  => array( 'sidebar' => 'simple-cta' ),
        'contact'     => array( 'sidebar' => 'contact' ), 
    );
    
    $enabled_section = array();
    
    if( $ed_banner == 'static_nl_banner' || $ed_banner == 'slider_banner' ) array_push( $enabled_section, 'banner' );
    
    foreach( $sections as $k => $v ){
        if( array_key_exists( 'sidebar', $v ) ){
            if( is_active_sidebar( $v['sidebar'] ) ) array_push( $enabled_section, $v['sidebar'] );
        }else{
            if( get_theme_mod( 'ed_' . $v['section'] . '_section', true ) ) array_push( $enabled_section, $v['section'] );
        }
    }  
    
    return apply_filters( 'blossom_coach_home_sections', $enabled_section );
}

/** Blossom Coach Fonts URL */
function blossom_coach_fonts_url(){
    $fonts_url = '';
    
    $primary_font       = get_theme_mod( 'primary_font', 'Montserrat' );
    $ig_primary_font    = blossom_coach_is_google_font( $primary_font );    
    $secondary_font     = get_theme_mod( 'secondary_font', 'Open Sans' );
    $ig_secondary_font  = blossom_coach_is_google_font( $secondary_font );    
    $site_title_font    = get_theme_mod( 'site_title_font', array( 'font-family'=>'Montserrat', 'variant'=>'700' ) );
    $ig_site_title_font = blossom_coach_is_google_font( $site_title_font['font-family'] );
        
    /* Translators: If there are characters in your language that are not
    * supported by respective fonts, translate this to 'off'. Do not translate
    * into your own language.
    */
    $primary    = _x( 'on', 'Primary Font: on or off', 'life-coach' );
    $secondary  = _x( 'on', 'Secondary Font: on or off', 'life-coach' );
    $site_title = _x( 'on', 'Site Title Font: on or off', 'life-coach' );
    
    if ( 'off' !== $primary || 'off' !== $secondary || 'off' !== $site_title ) {
        
        $font_families = array();
     
        if ( 'off' !== $primary && $ig_primary_font ) {
            $primary_variant = blossom_coach_check_varient( $primary_font, 'regular', true );
            if( $primary_variant ){
                $primary_var = ':' . $primary_variant;
            }else{
                $primary_var = '';    
            }            
            $font_families[] = $primary_font . $primary_var;
        }
         
        if ( 'off' !== $secondary && $ig_secondary_font ) {
            $secondary_variant = blossom_coach_check_varient( $secondary_font, 'regular', true );
            if( $secondary_variant ){
                $secondary_var = ':' . $secondary_variant;    
            }else{
                $secondary_var = '';
            }
            $font_families[] = $secondary_font . $secondary_var;
        }
        
        if ( 'off' !== $site_title && $ig_site_title_font ) {
            
            if( ! empty( $site_title_font['variant'] ) ){
                $site_title_var = ':' . blossom_coach_check_varient( $site_title_font['font-family'], $site_title_font['variant'] );    
            }else{
                $site_title_var = '';
            }
            $font_families[] = $site_title_font['font-family'] . $site_title_var;
        }
        
        $font_families = array_diff( array_unique( $font_families ), array('') );
        
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),            
        );
        
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }
     
    return esc_url_raw( $fonts_url );
}


/** Overwriting Testimonial Widget */
add_filter( 'blossom_testimonial_widget_filter', 'life_coach_testimonial_widget', 10, 3);
function life_coach_testimonial_widget( $html, $args, $instance ){
	$obj = new BlossomThemes_Toolkit_Functions();
    $name   = ! empty( $instance['name'] ) ? $instance['name'] : '' ;        
    $designation   = ! empty( $instance['designation'] ) ? $instance['designation'] : '' ;        
    $testimonial = ! empty( $instance['testimonial'] ) ? $instance['testimonial'] : '';
	$image   = ! empty( $instance['image'] ) ? $instance['image'] : '';
	if( $image ){
            /** Added to work for demo testimonial compatible */
            $attachment_id = $image;
            if ( !filter_var( $image, FILTER_VALIDATE_URL ) === false ) {
                $attachment_id = $obj->bttk_get_attachment_id( $image );
            }

            $icon_img_size = apply_filters('bttk_testimonial_icon_img_size','thumbnail');
            $image_array   = wp_get_attachment_image_src( $attachment_id, $icon_img_size);
            $image         = preg_match('/(^.*\.jpg|jpeg|png|gif|ico*)/i', $image_array[0]);
            $fimg_url      = $image_array[0]; 
    }
	?>
	<div class="bttk-testimonial-holder">
                <div class="bttk-testimonial-inner-holder">
                    <?php if( $image ){ ?>
                        <div class="img-holder">
                            <img src="<?php echo esc_url( $fimg_url ); ?>" alt="<?php echo esc_attr( $name ); ?>" />
                        </div>
                    <?php }?>

        			<div class="testimonial-meta">
                           <?php 
                                if( $name ) echo '<span class="name">' . esc_html( $name ) . '</span>';
                                if( isset( $designation ) && $designation!='' ){
                                    echo '<span class="designation">' . esc_html( $designation ) . '</span>';
                                }
                            ?>
                    </div> 
                    <div class="text-holder">
                                                     
                        <?php if( $testimonial ) echo '<div class="testimonial-content">' . wpautop( wp_kses_post( $testimonial ) ) . '</div>'; ?>
                    </div>
                </div>
    </div>
<?php
}

/** Blossom Coach Dynamic CSS */
function life_coach_dynamic_css(){
    
    $primary_font    = get_theme_mod( 'primary_font', 'Montserrat' );
    $primary_fonts   = blossom_coach_get_fonts( $primary_font, 'regular' );
    $secondary_font  = get_theme_mod( 'secondary_font', 'Open Sans' );
    $secondary_fonts = blossom_coach_get_fonts( $secondary_font, 'regular' );
    
    $site_title_font      = get_theme_mod( 'site_title_font', array( 'font-family'=>'Montserrat', 'variant'=>'700' ) );
    $site_title_fonts     = blossom_coach_get_fonts( $site_title_font['font-family'], $site_title_font['variant'] );
    $site_title_font_size = get_theme_mod( 'site_title_font_size', 40 );
    
    echo "<style type='text/css' media='all'>"; ?>
    
    /*Typography*/
    body,
    button,
    input,
    select,
    optgroup,
    textarea, section[class*="-section"] .widget_blossom_client_logo_widget .widget-title, .blog-section article .entry-meta, 
    .btn-link, .widget.widget_blossomthemes_stat_counter_widget .widget-title, .single .entry-meta, 
    .portfolio-text-holder .portfolio-img-title {
        font-family : <?php echo wp_kses_post( $primary_fonts['font'] ); ?>;
    }
    
    .site-title, 
    .site-title-wrap .site-title{
        font-size   : <?php echo absint( $site_title_font_size ); ?>px;
        font-family : <?php echo wp_kses_post( $site_title_fonts['font'] ); ?>;
        font-weight : <?php echo esc_html( $site_title_fonts['weight'] ); ?>;
        font-style  : <?php echo esc_html( $site_title_fonts['style'] ); ?>;
    }
    
    /*Typography*/
    h1, h2, h3, h4, h5, h6, 
    section[class*="-section"] .widget .widget-title,
section[class*="-section"] .widget_blossomtheme_featured_page_widget .section-subtitle,
.section-title, .comment-body b.fn, .comment-body .reply .comment-reply-link, .single .navigation .nav-links, 
.site-header .header-search label.screen-reader-text, .btn-readmore, .btn-readmore:visited, .bttk-testimonial-holder .name, 
.pricing-block .price, .entry-meta, #primary .widget_blossomtheme_featured_page_widget .section-subtitle, 
.widget_blossomthemes_stat_counter_widget .hs-counter, .widget_bttk_description_widget .bttk-team-holder .name, 
.bttk-team-inner-holder-modal .name, .page-header .subtitle, .dropcap, .error-404 .error-num, .error-404 a.bttn, 
.related-portfolio-title {
		font-family: <?php echo wp_kses_post( $secondary_fonts['font'] ); ?>;
	}
    <?php if( blossom_coach_is_woocommerce_activated() ) { ?>
    	.woocommerce div.product .product_title,
    	.woocommerce div.product .woocommerce-tabs .panel h2{
			font-family: <?php echo wp_kses_post( $secondary_fonts['font'] ); ?>;
    	}    
    <?php } ?>
           
    <?php echo "</style>";
}
add_action( 'wp_head', 'life_coach_dynamic_css', 100 );

/**
 * Function for sanitizing Hex color 
 */
function life_coach_sanitize_hex_color( $color ){
	if ( '' === $color )
		return '';

    // 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
		return $color;
}

/**
 * convert hex to rgb
 * @link http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
*/
function life_coach_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

function blossom_coach_footer_bottom(){ ?>
    <div class="bottom-footer">
		<div class="wrapper">
			<div class="copyright">            
            <?php
                blossom_coach_get_footer_copyright();
                echo '<a href="' . esc_url( 'https://blossomthemes.com/downloads/life-coach-free-wordpress-theme/' ) .'" rel="author" target="_blank">' . esc_html__( ' Life Coach', 'life-coach' ) . '</a>' . esc_html__( ' by Blossom Themes.', 'life-coach' );                
                printf( esc_html__( ' Powered by %s', 'life-coach' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'life-coach' ) ) .'" target="_blank">WordPress</a>.' );
                if ( function_exists( 'the_privacy_policy_link' ) ) {
                    the_privacy_policy_link();
                }
            ?>               
            </div>
		</div><!-- .wrapper -->
	</div><!-- .bottom-footer -->
    <?php
}
