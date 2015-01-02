<?php

if ( ! class_exists( 'WPIC_Helpers' ) ) {

	/**
	 * Helper functions for working with WordPress
	 *
	 *
	 * @since 4.0.0
	 */
	class WPIC_Helpers {

		public function __construct() {

		}

		public static function send_ajax_response( $content ) {
			if( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				die($content);
			}
		}

		public static function is_ajax() {
			return ( self::get_var( 'action' ) || self::post_var( 'action' ) );
		}

		public static function ajax_return( $results ) {
			if ( self::is_ajax() ) { die( $results ); }
			else { return $results; }
		}

		public static function get_var( $var ) {
			if ( isset ( $_GET[ $var ] ) ) { return self::sanitize_user_input( $var, $_GET[ $var ] ); }
			return false;
		}

		public static function post_var( $var ) {
			if ( isset ( $_POST[ $var ] ) ) { return self::sanitize_user_input( $var, $_POST[ $var ] ); }
			return false;
		}

		/**
		 * Get a layout
		 *
		 * Wrapper function for WPIC_Helpers::echo_layout that returns the file content as a string using output buffering
		 *
		 * @since 0.0.1
		 *
		 * @see WPIC_Helpers::echo_layout
		 *
		 * @param string $path Path to layout file, relative to layouts folder
		 * @return string File contents
		 */
		public static function get_layout( $path ) {
			ob_start();

			self::echo_layout( $path );

			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;
		}

		/**
		 * Echoes a layout
		 *
		 * Echoes a layout from the plugins layout folder, uses require to maintain PHP functionality embedded in layouts
		 *
		 * @since 0.0.1
		 *
		 * @param string $path Path to layout file, relative to layouts folder
		 * @return void
		 */
		public static function echo_layout( $path ) {
			require_once( __DIR__ . '/../layouts' . $path );
		}

		/**
		 * Sanitize user input
		 *
		 * Uses standard WordPress functions to sanitize user input. Handles arrays and will use email sanitization if the
		 * key includes the string `email`
		 *
		 * @since 4.0.0
		 *
		 * @see sanitize_text_field(), sanitize_email()
		 * @global type $varname Description.
		 * @global type $varname Description.
		 *
		 * @param type $var Description.
		 * @param type $var Optional. Description.
		 * @return type Description.
		 */
		public static function sanitize_user_input( $key, $value ) {
			if ( is_array( $value ) ) {
				$return = array();
				foreach ( $value as $arr_key => $arr_value ) {
					$return[$arr_key] = self::sanitize_user_input( $arr_key, $arr_value );
				}
				return $return;
			}
			if ( strpos( $key, 'email' ) !== FALSE ) { return sanitize_email( $value ); }
			return sanitize_text_field( $value );
		}

	} // END class WPIC_Helpers'
} // end if

if ( class_exists( 'WPIC_Helpers'  ) ) {
	$WPIC_Helpers = new WPIC_Helpers();
}