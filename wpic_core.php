<?php

/**
 * Core functionality and helper functions for the WP Instagram Comments plugin. All other classes should extend this one
 *
 *
 *
 * @since 0.0.1
 */

if ( ! class_exists( 'WPIC_Core') ) {

	class WPIC_Core {

		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_admin_settings_page' ) );

		}

		/**
		 * Retrieve GET variable. Variable passed through sanitization.
		 *
		 * @see WPIC_Core->sanitize
		 *
		 * @since 0.0.1
		 *
		 * @param string $var Name of variable to get
		 * @return false if variable does not exist, mixed otherwise
		 */
		public function get_var( $var ) {
			if ( isset ( $_GET[ $var ] ) ) { return $this->sanitize( $var, $_GET[ $var ] ); }
			return false;
		}

		/**
		 * Retrieve POSTed variable. Variable passed through sanitization.
		 *
		 * @see WPIC_Core->sanitize
		 *
		 * @since 0.0.1
		 *
		 * @param string $var Name of variable to get
		 * @return false if variable does not exist, mixed otherwise
		 */
		public function get_post_var( $var ) {
			if ( isset ( $_POST[ $var ] ) ) { return $this->sanitize( $var, $_POST[ $var ] ); }
			return false;
		}

		/**
		 * Sanitize a variable
		 *
		 * Uses standard WordPress sanitization functions to clean variables retrieved through GET and POST. Handles
		 * arrays and email values
		 *
		 * @since 0.0.1
		 *
		 * @param string $key Name of the variable to sanitize. If 'key' includes the string 'email' it will be treated with the email sanitization filter rather than the text
		 * @param mixed $value Value of the variable to sanitize
		 * @return mixed Sanitized $value
		 */
		public function sanitize( $key, $value ) {
			if ( is_array( $value ) ) {
				$return = array();
				foreach ( $value as $arr_key => $arr_value ) {
					$return[$arr_key] = $this->sanitize( $arr_key, $arr_value );
				}
				return $return;
			}
			if ( strpos( $key, 'email' ) !== FALSE ) { return sanitize_email( $value ); }
			return sanitize_text_field( $value );
		}

		/**
		 * Get a layout
		 *
		 * Wrapper function for WPIC_Core::echo_layout that returns the file content as a string using output buffering
		 *
		 * @since 0.0.1
		 *
		 * @see WPIC_Core::echo_layout
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
			require_once( __DIR__ . '/layouts' . $path );
		}


		/*
		 * Administration page registration
		 */

		public function register_admin_settings_page() {
			add_options_page(
				'WP Instagram Comments',
				'Instagram Comments',
				'manage_options',
				'wpic-options',
				array( $this, 'output_admin_settings_page' )
			);
		}

		public function output_admin_settings_page() {
			self::echo_layout( '/admin/wpic-options.php' );
		}

	} // END class WPIC_Core

} // end if

if ( class_exists( 'WPIC_Core' ) ) {
	$WPIC_Core = new WPIC_Core();
}