<?php

/**
 * Core functionality and helper functions for the WP Instagram Comments plugin
 *
 * Authentication functionality for the Instagram API
 *
 * @since 0.0.1
 */

if ( ! class_exists( 'WPIC_Core') ) {

	class WPIC_Core {

		public function __construct() {
			// Client enqueues
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_client_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_client_scripts' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_client_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_client_scripts' ) );
		}

		public static function activate() {

		}

		public static function deactivate() {

		}

		public function enqueue_client_styles() {

		}

		public function enqueue_client_scripts() {

		}

		public function enqueue_admin_styles() {

		}

		public function enqueue_admin_scripts() {

		}

	} // END class WPIC_Core

} // end if

if ( class_exists( 'WPIC_Core' ) ) {
	$WPIC_Core = new WPIC_Core();
}