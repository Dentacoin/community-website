<?php

/**
 * Handles the options access of the plugin
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.3.0
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 */

/**
 * Allows an easy access to plugin options/settings
 *
 * @package    WPTelegram_Widget
 * @subpackage WPTelegram_Widget/includes
 * @author     Manzoor Wani
 */
class WPTelegram_Widget_Options implements ArrayAccess {

	/**
	 * Plugin option key saved in the database
	 *
	 * @since 1.0.0
	 * @var string the option key
	 */
	protected $option_key;

	/**
	 * All plugin options
	 *
	 * @since 1.0.0
	 * @var array Contains all the plugin options
	 */
	protected $data;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * 
	 * @param	string	$option_key
	 */
	public function __construct( $option_key = '' ) {
		// make sure we have an array to avoid adding values to null
		$this->data = array();

		if ( ! empty( $option_key ) ) {
			$this->set_option_key( $option_key );
		}
	}

	/**
	 * Checks if an option key exists
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Option key
	 *
	 * @return bool Whether the option key exists.
	 */
	public function exists( $key ) {
		return array_key_exists( $key, $this->get_data() );
	}

	/**
	 * Retrieves an option by key
	 *
	 * @since 1.0.0
	 *
	 * @param  string $key	 Options array key
	 * @param  mixed  $default Optional default value
	 *
	 * @return mixed		   Option value
	 */
	public function get( $key = '', $default = false ) {
		if ( 'all' === $key || empty( $key ) ) {
			$value = $this->data;
		} else {
			$value = $this->exists( $key ) ? $this->data[ $key ] : $default;
		}

		return apply_filters( strtolower( __CLASS__ ) . "_{$this->option_key}_get_{$key}", $value, $default );
	}

	/**
	 * Sets an option by key
	 *
	 * @since 1.0.0
	 *
	 * @param  string $key	 Options array key
	 * @param  mixed  $value Option value
	 *
	 * @return mixed		   Option value
	 */
	public function set( $key, $value = '' ) {

		if ( empty( $this->option_key ) ) {
			return false;
		}

		$this->data[ $key ] = apply_filters( strtolower( __CLASS__ ) . "_{$this->option_key}_set_{$key}", $value );

		return $this->update();
	}

	/**
	 * Unset/remove an option by key
	 *
	 * @since 1.0.0
	 *
	 * @param  string $key	 Options array key
	 *
	 * @return mixed		   Option value
	 */
	public function unset_key( $key ) {

		unset( $this->data[ $offset ] );

		return $this->update();
	}

	/**
	 * Updates the options in the database
	 *
	 * @since 1.0.0
	 */
	public function update() {

		// make sure we have something to work upon
		if ( ! empty( $this->option_key ) ) {

			return update_option( $this->option_key, $this->get_data() );
		}
		return false;
	}

	/**
	 * Set the option key
	 *
	 * @since 1.0.0
	 * 
	 * @param string	$option_key Option name in the database
	 */
	public function set_option_key( $option_key ) {
		$this->option_key = $option_key;
		$this->set_data();
	}

	/**
	 * Sets all options.
	 *
	 * @since 1.0.0
	 *
	 */
	public function set_data( array $options = array() ) {
		if ( empty( $options ) && ! empty( $this->option_key ) ) {
			$this->data = get_option( $this->option_key, array() );
		} else {
			$this->data = (array) $options;
		}
	}

	/**
	 * Get the option key
	 *
	 * @since 1.0.0
	 */
	public function get_option_key() {
		return $this->option_key;
	}

	/**
	 * Gets all options.
	 *
	 * @since 1.0.0
	 *
	 */
	public function get_data() {
		return (array) $this->get();
	}

	/**
	 * Magic method for accessing options as object props
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Options array key
	 *
	 * @return mixed Value of the option
	 */
	public function __get( $key ) {
		return $this->get( $key );
	}

	/**
	 * Magic method for setting options as object props
	 *
	 * @since 1.0.0
	 *
	 * @param string $key	Options array key
	 * @param string $value	Option value
	 */
	public function __set( $key, $value ) {
        return $this->set( $key, $value );
    }

	/**
	 * Allows the object being called as a function
	 * to retrieve an option
	 *
	 * @since 1.0.0
	 *
	 * @param  string $key	 Options array key
	 *
	 * @return mixed		 Option value
	 */
	public function __invoke( $key ) {
		return $this->get( $key );
	}

	/**
	 * Allows the object being treated as string
	 *
	 * @since 1.0.0
	 *
	 * @return string		   json encoded
	 */
    public function __toString() {
        return json_encode( $this->get_data() );
    }

	/**
	 * Checks if an option key exists
	 *
	 * @since 1.0.0
	 *
	 * @param string $offset Option key
	 *
	 * @return bool Whether the option key exists.
	 */
	public function offsetExists( $offset ) {
		return $this->exists( $offset );
	}

	/**
	 * Retrieves an option
	 *
	 * @since 1.0.0
	 *
	 * @param string $offset option key
	 *
	 * @return mixed|false Value if set, false otherwise.
	 */
	public function offsetGet( $offset ) {
		return $this->get( $offset );
	}

	/**
	 * Sets an option key
	 *
	 * @since 1.0.0
	 *
	 * @param string $offset option key
	 * @param mixed  $value  option value
	 */
	public function offsetSet( $offset, $value ) {
		return $this->set( $offset, $value );
	}

	/**
	 * Removes an option key
	 *
	 * @since 1.0.0
	 *
	 * @param string $offset option key
	 */
	public function offsetUnset( $offset ) {
		return $this->unset_key( $offset );
	}
}