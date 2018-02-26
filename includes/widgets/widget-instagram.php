<?php

class Shapla_Widget_Instagram extends ShaplaTools_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		$this->widget_id          = 'shapla-instagram';
		$this->widget_cssclass    = 'widget_shapla_instagram';
		$this->widget_description = __( 'Display your latest Instagrams photos.', 'shaplatools' );
		$this->widget_name        = __( 'Shapla Instagram Photos', 'shaplatools' );
		$this->settings           = array(
			'title'            => array(
				'type'  => 'text',
				'std'   => __( 'Instagram Photos', 'shaplatools' ),
				'label' => __( 'Title:', 'shaplatools' ),
			),
			'username'         => array(
				'type'        => 'text',
				'std'         => null,
				'placeholder' => 'myusername',
				'label'       => __( 'Instagram Username:', 'shaplatools' ),
			),
			'count'            => array(
				'type'  => 'number',
				'std'   => 9,
				'label' => __( 'Photo Count (max 12):', 'shaplatools' ),
				'step'  => 1,
				'min'   => 1,
				'max'   => 12,
			),
			'size'             => array(
				'type'    => 'select',
				'std'     => 'thumbnail',
				'label'   => __( 'Photo Size:', 'shaplatools' ),
				'options' => array(
					'thumbnail' => __( 'Thumbnail', 'shaplatools' ),
					'small'     => __( 'Small', 'shaplatools' ),
					'large'     => __( 'Large', 'shaplatools' ),
					'original'  => __( 'Original', 'shaplatools' ),
				),
			),
			'cachetime'        => array(
				'type'  => 'number',
				'std'   => 2,
				'label' => __( 'Cache time (in hours):', 'shaplatools' ),
				'step'  => 1,
				'min'   => 1,
				'max'   => 500,
			),
			'follow_link_show' => array(
				'type'  => 'checkbox',
				'std'   => false,
				'label' => __( 'Include link to Instagram page?', 'shaplatools' ),
			),
			'follow_link_text' => array(
				'type'  => 'text',
				'std'   => 'Follow on Instagram',
				'label' => __( 'Link Text:', 'shaplatools' ),
			),
		);

		parent::__construct();
	}

	/**
	 * Display widget content
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {

		if ( $this->get_cached_widget( $args ) ) {
			return;
		}

		$title            = isset( $instance['title'] ) ? esc_html( $instance['title'] ) : __( 'Instagram Photos', 'shaplatools' );
		$username         = isset( $instance['username'] ) ? esc_html( $instance['username'] ) : null;
		$count            = isset( $instance['count'] ) ? absint( $instance['count'] ) : 9;
		$cachetime        = isset( $instance['cachetime'] ) ? absint( $instance['cachetime'] ) : 2;
		$size             = isset( $instance['size'] ) ? esc_html( $instance['size'] ) : 'thumbnail';
		$follow_link_show = isset( $instance['follow_link_show'] ) ? (bool) $instance['follow_link_show'] : false;
		$follow_link_text = isset( $instance['follow_link_text'] ) ? $instance['follow_link_text'] : __( 'Follow on Instagram', 'shaplatools' );

		// Get Instagrams
		$instagram = $this->scrape_instagram( $username, $cachetime );

		ob_start();

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		// And if we have Instagrams
		if ( is_array( $instagram ) ) {

			// slice list down to required limit.
			$instagram = array_slice( $instagram, 0, $count );

			?>

            <div class="instagram-row">
				<?php
				foreach ( $instagram as $image ) {

					$html = '<div class="instagram-col ' . esc_attr( $size ) . '">';
					$html .= '<a href="' . esc_url( $image['link'] ) . '">';
					$html .= sprintf(
						'<img class="instagram-image" src="%1$s" alt="%2$s" title="%2$s" />',
						esc_url( $image[ $size ] ),
						esc_html( $image['description'] )
					);
					$html .= '</a>';
					$html .= '</div>';

					echo apply_filters( 'shaplatools_instagram_widget_image_html', $html, $image );
				}
				?>
            </div>

			<?php if ( $follow_link_show && $follow_link_text ) { ?>
                <a class="instagram-follow-link" href="https://instagram.com/<?php echo esc_html( $username ); ?>">
					<?php echo $follow_link_text; ?>
                </a>
			<?php } ?>

		<?php }

		echo $args['after_widget'];

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}

	/**
	 * Scrapge Instagram data from webpage.
	 * Based on https://gist.github.com/cosmocatalano/4544576
	 *
	 * @param  string $username Instagram username.
	 * @param  string $cachetime Cache time.
	 *
	 * @return mixed
	 */
	protected function scrape_instagram( $username, $cachetime ) {
		$username       = trim( strtolower( $username ) );
		$transient_name = 'shaplatools_instagram_' . sanitize_title_with_dashes( $username );
		$instagram      = get_transient( $transient_name );

		if ( false === $instagram ) {
			switch ( substr( $username, 0, 1 ) ) {
				case '#':
					$url = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
					break;

				default:
					$url = 'https://instagram.com/' . str_replace( '@', '', $username );
					break;
			}

			$remote = wp_remote_get( $url );

			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'shaplatools' ) );
			}

			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'shaplatools' ) );
			}

			$shards      = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json  = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'shaplatools' ) );
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'];
			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'shaplatools' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'shaplatools' ) );
			}

			$instagram = array();

			foreach ( $images as $image ) {
				switch ( substr( $username, 0, 1 ) ) {
					case '#':
						if ( true === $image['node']['is_video'] ) {
							$type = 'video';
						} else {
							$type = 'image';
						}

						$caption = __( 'Instagram Image', 'stag' );
						if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
							$caption = $image['node']['edge_media_to_caption']['edges'][0]['node']['text'];
						}

						$instagram[] = array(
							'description' => $caption,
							'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
							'time'        => $image['node']['taken_at_timestamp'],
							'comments'    => $image['node']['edge_media_to_comment']['count'],
							'likes'       => $image['node']['edge_liked_by']['count'],
							'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
							'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
							'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
							'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
							'type'        => $type,
						);
						break;

					default:
						if ( true === $image['is_video'] ) {
							$type = 'video';
						} else {
							$type = 'image';
						}

						$caption = __( 'Instagram Image', 'shaplatools' );
						if ( ! empty( $image['caption'] ) ) {
							$caption = $image['caption'];
						}

						$instagram[] = array(
							'description' => $caption,
							'link'        => trailingslashit( '//instagram.com/p/' . $image['code'] ),
							'time'        => $image['date'],
							'comments'    => $image['comments']['count'],
							'likes'       => $image['likes']['count'],
							'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['thumbnail_resources'][0]['src'] ),
							'small'       => preg_replace( '/^https?\:/i', '', $image['thumbnail_resources'][2]['src'] ),
							'large'       => preg_replace( '/^https?\:/i', '', $image['thumbnail_resources'][4]['src'] ),
							'original'    => preg_replace( '/^https?\:/i', '', $image['display_src'] ),
							'type'        => $type,
						);
						break;
				}
			}  // End foreach().

			// Do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram            = base64_encode( serialize( $instagram ) );
				$transient_expiration = HOUR_IN_SECONDS * $cachetime;
				set_transient( $transient_name, $instagram, $transient_expiration );
			}
		}

		if ( ! empty( $instagram ) ) {
			return unserialize( base64_decode( $instagram ) );
		} else {
			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'shaplatools' ) );
		}
	}

	public static function register() {
		register_widget( __CLASS__ );
	}
}

add_action( 'widgets_init', array( 'Shapla_Widget_Instagram', 'register' ) );
