<?php

if ( ! class_exists( 'WPIC_Helpers' ) ) { require_once( __DIR__ . '/wpic-helpers.php' ); }

/**
 * Handles admin meta boxes and settings pages for WP Instagram Comments
 *
 * @since 0.0.1
 */
if ( ! class_exists( 'WPIC_Admin') ) {

	class WPIC_Admin extends WP_Instagram_Comments {

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'register_admin_settings_page' ) );
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

		/*
		 * Administration page layout
		 */
		public function output_admin_settings_page() {
			WPIC_Helpers::echo_layout( '/admin/wpic-options.php' );
		}

		/**
		 * Add meta box to input an instagram post link to pull comments from
		 *
		 * @since 0.0.1
		 *
		 * @see hook 'add_meta_boxes', add_meta_box()
		 * @param string $post_type type of post being displayed
		 * @return void
		 */
		public function add_wpic_meta_box( $post_type ) {
			add_meta_box(
				parent::WPIC_META_KEY,
				'Instagram Comments',
				array( $this, 'render_wpic_meta_box' ),
				'post'
			);
		}

		/**
		 * Output WPIC meta box
		 *
		 * @since 0.0.1
		 *
		 * @see WPIC_Admin->add_wpic_meta_box()
		 * @param WP_Post $post Post object where meta box is being inserted
		 * @return void
		 */
		public function render_wpic_meta_box( $post ) {
			wp_nonce_field( parent::WPIC_META_KEY, 'wp_instagram_comments_nonce' );

			$value = get_post_meta( $post->ID, parent::WPIC_META_KEY, true );

			echo '<label for="wp_instagram_comments">' . __( 'Enter the URL of any instagram posts you\'d like to link here, separated by commas' ) . '</label><br>';
			echo '<input style="width: 100%;" type="text" id="wp_instagram_comments_input" name="wp_instagram_comments" value="' . esc_attr( $value ) . '">';
		}

		/**
		 * Save data from WPIC meta box
		 *
		 * @since 0.0.1
		 *
		 * @see hook 'save_post'
		 * @param int $post_id ID of post being saved
		 * @return void
		 */
		public function save_wpic_meta( $post_id ) {
			if ( ! isset( $_POST[ 'wp_instagram_comments_nonce' ] ) ) { return; }
			if ( ! wp_verify_nonce( $_POST[ 'wp_instagram_comments_nonce' ], parent::WPIC_META_KEY ) ) { return; }
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
			if ( ! current_user_can( 'edit_post', $post_id ) ) { return ; }

			$wpic_data = WPIC_Helpers::post_var( parent::WPIC_META_KEY );

			// TODO: Verify that this is a valid URL to Instagram
			if ( ! filter_var( $wpic_data, FILTER_VALIDATE_URL )  ) { return; }

			update_post_meta( $post_id, parent::WPIC_META_KEY, $wpic_data );
		}

	} // END class WPIC_Admin

} // end if

if ( class_exists( 'WPIC_Admin' ) ) {
	$WPIC_Admin = new WPIC_Admin();

	add_action( 'add_meta_boxes', array( $WPIC_Admin, 'add_wpic_meta_box' ) );
	add_action( 'save_post', array( $WPIC_Admin, 'save_wpic_meta' ) );
}