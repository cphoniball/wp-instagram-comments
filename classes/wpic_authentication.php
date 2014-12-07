<?php
/**
 * Handle authentication with the Instagram API
 *
 * Authentication functionality for the Instagram API
 *
 * @since 0.0.1
 */
if ( ! class_exists( 'WPIC_Authentication') ) {

	class WPIC_Authentication extends WPIC_Core {

		private $client_id;
		private $client_secret;
		private $redirect_uri;
		private $auth_endpoint;

		public function __construct() {
			$this->client_id = 'a1d83a073f2f469aaf4f4d149728fda6';
			$this->client_secret = '4df6ea4c0f1844e58e179e2e0ae25db2j';
			$this->redirect_uri = 'http://tori.dev/wp-admin/options-general.php?page=wpic-options';
			$this->auth_endpoint = 'https://api.instagram.com/oauth/authorize/?client_id=' . $this->client_id . '&redirect_uri=' . $this->redirect_uri . '&response_type=code';
		}

		public function get_auth_endpoint() {
			return $this->auth_endpoint;
		}

	} // END class WPIC_Authentication

} // end if

if ( class_exists( 'WPIC_Authentication' ) ) {

	$wpic_auth = new WPIC_Authentication();

}