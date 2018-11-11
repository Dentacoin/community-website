<?php

    // Custom Header

    $defaults = array(
        'width'                  => 1100,
        'height'                 => 200,
        'flex-height'            => true,
        'flex-width'             => true,
    );

    add_theme_support( 'custom-header', $defaults );


    // Custom Backgrounds

    $defaults = array(
        'default-color'  => 'white',
    );

    add_theme_support( 'custom-background' , $defaults );


    // Navi

    add_action( 'after_setup_theme', 'dcn_register_nav' );

    function dcn_register_nav() {
      register_nav_menu('nav_main','Navbar left on the desktop');
      // register_nav_menu('nav_social','Navbar left in the footer');
      register_nav_menu('nav_secondary','Navbar right in the footer');
    }


      // Sidebars / Widgets

      add_action( 'widgets_init', 'dcn_register_sidebar' );

      function dcn_register_sidebar() {
          register_sidebar(
                array(
                    'name' => 'Main Sidebar',
                    'id' => 'sidebar-1',
                    'description' => 'Desktop version: to the right of the content respectively under the content',
                    'before_widget' => '<li id="%1$s" class="widget %2$s">',
                  	'after_widget'  => '</li>',
                  	'before_title'  => '<h5 class="widgettitle">',
                  	'after_title'   => '</h5>',
                    )
                  );
      }



      // Styles and Scripts.

      add_action( 'wp_enqueue_scripts', 'dcn_register_styles' );

      function dcn_register_styles() {

        	wp_register_style( 'normalize', get_template_directory_uri() . '/css/normalize.css' );
        	wp_enqueue_style( 'normalize' );

          wp_register_style( 'style', get_stylesheet_uri());
        	wp_enqueue_style( 'style' );
      }
      // function dcn_register_scripts() {
      //
      //     dcn_register_script()
      //     ...
      // }






      // HTML5

      $args = array(
          'search-form',
          'comment-form',
          'comment-list',
          'gallery',
          'caption'
      );
      add_theme_support( 'html5', $args );


      // Customizer
      require_once(get_template_directory() . '/customizer.php');






    // Enable *.svg - upload

  function kb_svg ( $svg_mime ){
	$svg_mime['svg'] = 'image/svg+xml';
	return $svg_mime;
  }

  add_filter( 'upload_mimes', 'kb_svg' );
 ?>
