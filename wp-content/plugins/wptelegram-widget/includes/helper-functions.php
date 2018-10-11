<?php

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function wptelegram_widget_get_option( $key = '', $default = false ) {
	if ( function_exists( 'cmb2_get_option' ) ) {
		// Use cmb2_get_option as it passes through some key filters.
		return cmb2_get_option( 'wptelegram_widget', $key, $default );
	}
	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( 'wptelegram_widget', $default );
	$val = $default;
	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}
	return $val;
}

if ( ! function_exists( 'wptelegram_is_plugin_active' ) ) {
	function wptelegram_is_plugin_active( $plugin ) {
	    if ( in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) ) {
	    	return true;
	    }
	    if ( is_multisite() ){
	        $plugins = get_site_option( 'active_sitewide_plugins' );
		    if ( isset( $plugins[ $plugin ] ) ){
		        return true;
		    }
	    }
	    return false;
	}
}