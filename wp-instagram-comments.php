<?php
/*
 * Plugin Name: WP Instagram Comments
 * Description: Allows users to pull in comments from Instagram posts to display alongside their WordPress comments
 * Version: 0.0.1
 * Author: Chris Honiball
 * Author URI: http://chrishoniball.com
 * License: GPL2
 */

if ( ! class_exists( 'WP_Instagram_Comments') ) {

	class WP_Instagram_Comments {

		const WPIC_META_KEY = 'wp_instagram_comments';

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

	} // END class WP_Instagram_Comments

} // end if

if ( class_exists( 'WP_Instagram_Comments' ) ) {
	$WP_Instagram_Comments = new WP_Instagram_Comments();

	require_once( __DIR__ . '/vendor/shuber/curl/curl.php' );

	require_once( __DIR__ . '/classes/wpic_helpers.php' );
	require_once( __DIR__ . '/classes/wpic_authentication.php' );
	require_once( __DIR__ . '/classes/wpic_admin.php' );
	require_once( __DIR__ . '/classes/wpic_comments.php' );
}