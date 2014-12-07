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