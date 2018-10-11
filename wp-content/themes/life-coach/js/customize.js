jQuery(document).ready(function($){
    /* Move widgets to their respective sections */
    wp.customize.section( 'sidebar-widgets-service' ).panel( 'frontpage_settings' );
    wp.customize.section( 'sidebar-widgets-service' ).priority( '22' );
});

