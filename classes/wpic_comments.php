<?php

if ( ! class_exists( 'WPIC_Helpers' ) ) { require_once( __DIR__ . '/wpic-helpers.php' ); }

/**
 * Retrieves and outputs WPIC Comments
 *
 * @since 0.0.1
 */

if ( ! class_exists( 'WPIC_Comments') ) {

	class WPIC_Comments extends WP_Instagram_Comments {

		public function __construct() {
			add_filter( 'the_content', array( $this, 'append_instagram_comments' ) );
		}

		/**
		 * If an instagram post has been added to this post, appends Instagram comments
		 * to the end of the post content via 'the_content' filter
		 *
		 * @since 0.0.1
		 *
		 * @param string $content Optional. Content of the post being output.
		 * @return string Modified post content with instagram comments attached, if any
		 */
		public function append_instagram_comments( $content = '' ) {
			global $post;
			$instagram_post = get_post_meta( $post->ID, parent::WPIC_META_KEY, true );

			if ( ! $instagram_post ) {
				return $content;
			}

			$comments = self::get_instagram_comments( $instagram_post );

			$comment_layout = WPIC_Helpers::get_layout( '/wpic-comments.php' );

			$content .= $comment_layout;

			return $content;
		}

		/**
		 * Given URL for an instagram post, retrieve the comments on that post
		 *
		 * @since 0.0.1
		 *
		 * @param string $url Full URL to the Instagram post to retrieve comments for
		 * @return type Description.
		 */
		public static function get_instagram_comments( $url ) {

		}

	} // END class WPIC_Comments

} // end if

if ( class_exists( 'WPIC_Comments' ) ) {
	$WPIC_Comments = new WPIC_Comments();
}