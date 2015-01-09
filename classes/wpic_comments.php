<?php

if ( ! class_exists( 'WPIC_Helpers' ) ) { require_once( __DIR__ . '/wpic-helpers.php' ); }
if ( ! class_exists( 'WPIC_Requests' ) ) { require_once( __DIR__ . '/wpic-requests.php' ); }

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

			$media = WPIC_Requests::get_media_by_url( $instagram_post );
			$comments = self::get_instagram_comments( $media['data']['id'] );

			if ( ! $comments ) {
				return $content;
			}

			$markup = '<div class="wpic-comments">';
			$markup .= '<header class="wpic-comments-header">Comments on this topic from Instagram</header>';

			$markup .= self::markup_instagram_post( $media['data'] );

			$markup .= '<ul class="wpic-comments-list">';

			foreach ( $comments as $comment ) {
				$markup .= self::markup_instagram_comment( $comment );
			}

			$markup .= '</ul>';
			$markup .= '</div>';

			$content .= $markup;

			return $content;
		}

		/**
		 * Returns instagram comment into HTML markup
		 *
		 * @since 0.1.0
		 *
		 * @param array $comment Array representing instagram comment
		 * @return string Comment marked up as a list item
		 */
		public static function markup_instagram_comment( $comment ) {
			$user = $comment['from'];

			$markup = '<li class="wpic-comment">';
			$markup .= '<div class="wpic-comment-image-wrapper"><img class="wpic-comment-image" src="' . $user['profile_picture'] . '"></div>';
			$markup .= '<div class="wpic-comment-content"><div class="wpic-commenter-name">' . $user['username'] . '</div>';
			$markup .= '<div class="wpic-comment-text">' . $comment['text'] . '</div></div>';
			$markup .= '</li>';

			return $markup;
		}

		/**
		 * Returns an instagram post as HTML markup
		 *
		 * @since 0.1.0
		 *
		 * @param array $media Array corresponding to the media item retrieved from Instagram
		 * @return string Post marked up as a div
		 */
		public static function markup_instagram_post( $media ) {
			$caption = $media['caption']['text'];
			$image_url = $media['images']['standard_resolution']['url'];
			$likes = $media['likes']['count'];
			$username = $media['user']['username'];


			$markup = '<div class="wpic-post">';
			$markup .= $caption;
			$markup .= $username;
			$markup .= $likes;
			$markup .= '<img src="' . $image_url . '">';
			$markup .= '</div>';

			return $markup;
		}

		/**
		 * Given URL for an instagram post, retrieve the comments on that post
		 *
		 * @since 0.0.1
		 *
		 * @param string $id Media ID for the Instagram post
		 * @return type Description.
		 */
		public static function get_instagram_comments( $id ) {
			$comments = WPIC_Requests::get_media_comments( $id );
			return $comments['data'];
		}

	} // END class WPIC_Comments

} // end if

if ( class_exists( 'WPIC_Comments' ) ) {
	$WPIC_Comments = new WPIC_Comments();
}