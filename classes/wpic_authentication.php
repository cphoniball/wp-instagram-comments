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

		protected $client_id;
		protected $client_secret;
		protected $redirect_uri;
		protected $auth_endpoint;

		public function __construct() {
			$this->client_id = 'a1d83a073f2f469aaf4f4d149728fda6';
			$this->client_secret = '4df6ea4c0f1844e58e179e2e0ae25db2';
			$this->redirect_uri = 'http://tori.dev/wp-admin/options-general.php?page=wpic-options';
			$this->auth_endpoint = 'https://api.instagram.com/oauth/authorize/?client_id=' . $this->client_id . '&redirect_uri=' . $this->redirect_uri . '&response_type=code';
			$this->access_token_endpoint = 'https://api.instagram.com/oauth/access_token';

			add_action( 'init', array( $this, 'receive_auth_request_redirect' ) );
		}

		protected function get_auth_endpoint() {
			return $this->auth_endpoint;
		}

		protected function get_access_token() {
			return get_option( 'wpic_access_token' );
		}

		/**
		 * Handle auth redirect from Instagram
		 *
		 * Receives the auth redirect from Instagram and handles as appropriate, displaying an error message if the request was denied
		 * or handing over control to the access token function
		 *
		 * @since 0.0.1
		 * @access protected
		 *
		 * @see WPIC_Authentication->request_access_token()
		 *
		 * @return void
		 */
		public function receive_auth_request_redirect() {
			$page = $this->get_var( 'page' );

			if ( $page !== 'wpic-options' ) {
				return;
			}

			$error = $this->get_var( 'error' );
			$code = $this->get_var( 'code' );

			if ( $error || ! $code ) {
				$reason = $this->get_var( 'error_reason' );
				$description = $this->get_var( 'error_description' );

				// handle error here... need to redirect to page and show error, explain why the app won't work
				return;
			}

			$this->request_access_token( $code );
		}

		/**
		 * Request oauth access token
		 *
		 * @since 0.0.1
		 *
		 * @param string $code Access code received from Instagram
		 * @return void
		 */
		protected function request_access_token( $code ) {
			$curl = new Curl();

			$response = $curl->post( $this->access_token_endpoint, array(
				'client_id' => $this->client_id,
				'client_secret' => $this->client_secret,
				'grant_type' => 'authorization_code',
				'redirect_uri' => $this->redirect_uri,
				'code' => $code
			) );

			print_r( $response->body );

			$status_code = $response->headers['Status-Code'];
			$body = json_decode( $response->body, true );

			// TODO: Add error-handling here in case any of the API client details are incorrect. To test, intentionally make an error in the
			// client secret and handle that error code

			$access_token = $body['access_token'];
			$username = $body['user']['username'];
			$user_id = $body['user']['id'];


			update_option( 'wpic_access_token', $access_token );
			update_option( 'wpic_username', $username );
			update_option( 'wpic_user_id', $user_id );
		}



	} // END class WPIC_Authentication

} // end if

if ( class_exists( 'WPIC_Authentication' ) ) {

	$wpic_auth = new WPIC_Authentication();

}