<?php

if ( ! class_exists( 'WPIC_Helpers' ) ) { require_once( __DIR__ . '/wpic-helpers.php' ); }
if ( ! class_exists( 'WPIC_Authentication' ) ) { require_once( __DIR__ . '/wpic-authentication.php' ); }

/**
 * Handles making requests to the Instagram API
 *
 * @since 0.0.1
 */
if ( ! class_exists( 'WPIC_Requests') ) {

	class WPIC_Requests extends WP_Instagram_Comments {

		const API_ENDPOINT = 'https://api.instagram.com/v1/';

		protected static $access_token;

		public function __construct() {
			$auth = new WPIC_Authentication();
			self::$access_token = $auth->get_access_token();
		}

		/**
		 * Given a response from the Curl object, either handle the error code or return the response body
		 *
		 * @since 0.1.0
		 *
		 * @param Curl Response $response Response object from a Curl request to the Instagram API
		 * @return mixed False if error occured, array of body response otherwise
		 */
		public static function handle_response( $response ) {
			return json_decode( $response->body, true );
		}

		/**
		 * Get basic information about the currently authenticated user
		 *
		 * @since 0.0.1
		 *
		 * @link http://instagram.com/developer/endpoints/users/
		 *
		 * @return array Data array representing the user
		 */
		public static function get_user() {
			$user_id = WPIC_Authentication::get_user_id();
			if ( ! $user_id ) { return false; }

			$url = self::API_ENDPOINT . 'users/' . $user_id . '/';

			$curl = new Curl();
			$response = $curl->get( $url, array( 'access_token' => self::$access_token ) );

			return self::handle_response( $response );
		}

		/**
		 * Get basic information about the currently auth'd users feed
		 *
		 * @since 0.0.1
		 *
		 * @link http://instagram.com/developer/endpoints/users/#get_users_feed
		 *
		 * @return array Data array representing the user's feed
		 */
		public static function get_user_feed() {
			$curl = new Curl();

			$url = self::API_ENDPOINT . 'users/self/feed';

			$response = $curl->get( $url, array( 'access_token' => self::$access_token ) );

			return self::handle_response( $response );
		}

		/**
		 * Get user recent media
		 *
		 * @since 0.0.1
		 * @link http://instagram.com/developer/endpoints/users/#get_users_media_recent
		 *
		 * @return mixed array Data array of user recent media, false if failed
		 */
		public static function get_user_recent_media() {
			$user_id = WPIC_Authentication::get_user_id();

			if ( ! $user_id ) { return false; }

			$url = self::API_ENDPOINT . 'users/' . $user_id . '/media/recent';

			$curl = new Curl();
			$response  = $curl->get( $url, array( 'access_token' => self::$access_token ) );

			return self::handle_response( $response );
		}

		/**
		 * Given the URL to a media item, return data about it.
		 *
		 * @since 0.0.1
		 *
		 * @param string $url URL to the media object on Instagram
		 * @return mixed Array of data about media object if successful, otherwise false
		 */
		public static function get_media_by_url( $url ) {
			$url_arr = explode( '/', $url );

			// note that instagram photo or video URLs are of the form http(s)://instagram.com/p/x234o14
			// where the section after the 'p' is the shortcode for that media item
			$p_index = array_search( 'p', $url_arr );

			if ( $p_index && ! empty( $url_arr[$p_index + 1] ) ) {
				$shortcode = $url_arr[$p_index + 1];
			}

			if ( ! $shortcode ) { return false; }

			$url = self::API_ENDPOINT . 'media/shortcode/' . $shortcode;

			$curl = new Curl();
			$response = $curl->get( $url, array( 'access_token' => self::$access_token ) );

			return self::handle_response( $response );
		}

		/**
		 * Given the ID of a media item, return the comments on it
		 *
		 * @since 0.0.1
		 *
		 * @param string $id ID of media object
		 * @return mixed Array of comments if successful, false otherwise
		 */
		public static function get_media_comments( $id ) {
			$url = self::API_ENDPOINT . 'media/' . $id . '/comments';

			$curl = new Curl();
			$response = $curl->get( $url, array( 'access_token' => self::$access_token ) );

			return self::handle_response( $response );
		}

	} // END class WPIC_Requests

} // end if

if ( class_exists( 'WPIC_Requests' ) ) {
	$WPIC_Requests = new WPIC_Requests();
}