<?php
/**
 * Utilities library
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPUM_Utils Class
 * A set of functions and tools that can be called from anywhere when needed.
 *
 * @since 1.0.0
 */
class WPUM_Utils {

	/**
	 * Gets submitted values and sanitize them.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @param $fields - array of all the submitted content that should be sanitized.
	 * @return void
	 */
	public static function sanitize_submitted_fields( $fields ) {

		$values = array();

		foreach ($fields as $field) {

			if ( method_exists( __CLASS__, "get_posted_{$field['type']}_field" ) ) {
				$values[ $field['id'] ] = call_user_func( __CLASS__ . "::get_posted_{$field['type']}_field", $field['value'] );
			} else {
				$values[ $field['id'] ] = self::sanitize_posted_field( $field['value'] );
			}

		}

		return $values;

	}

	/**
	 * Goes through fields and sanitizes them.
	 *
	 * @access public
	 * @param array|string $value The array or string to be sanitized.
	 * @since 1.0.0
	 * @return array|string $value The sanitized array (or string from the callback)
	 */
	public static function sanitize_posted_field( $value ) {
		// Decode URLs
		if ( is_string( $value ) && ( strstr( $value, 'http:' ) || strstr( $value, 'https:' ) ) ) {
			$value = urldecode( $value );
		}

		// Santize value
		$value = is_array( $value ) ? array_map( array( __CLASS__, 'sanitize_posted_field' ), $value ) : sanitize_text_field( stripslashes( trim( $value ) ) );

		return $value;
	}

	/**
	 * Get the value of a posted multiselect field
	 * @param array|string $value The array or string to be sanitized.
	 * @return array
	 */
	protected static function get_posted_multiselect_field( $value ) {
		return !empty( $value ) ? array_map( 'sanitize_text_field', $value ) : array();
	}

	/**
	 * Get the value of a posted textarea field
	 * @param array|string $value The array or string to be sanitized.
	 * @return string
	 */
	protected static function get_posted_textarea_field( $value ) {
		return !empty( $value ) ? wp_kses_post( trim( stripslashes( $value ) ) ) : '';
	}

	/**
	 * Get the value of a posted textarea field
	 * @param array|string $value The array or string to be sanitized.
	 * @return string
	 */
	protected static function get_posted_wp_editor_field( $value ) {
		return self::get_posted_textarea_field( $value );
	}

}