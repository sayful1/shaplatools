<?php

class Shapla_Flickr_Widget extends ShaplaTools_Widget {

	/**
	 * Shapla_Flickr_Widget constructor.
	 */
	public function __construct() {

		$this->widget_id          = 'shapla-flickr';
		$this->widget_css_class   = 'widget_shapla_flickr';
		$this->widget_description = __( 'Display your latest Flickr photos.', 'shaplatools' );
		$this->widget_name        = __( 'Shapla Flickr Photos', 'shaplatools' );
		$this->settings           = array(
			'title'          => array(
				'type'  => 'text',
				'std'   => 'Flickr Photos',
				'label' => __( 'Title:', 'shaplatools' ),
			),
			'flickr_id'      => array(
				'type'  => 'text',
				'std'   => null,
				'label' => __( 'Your Flickr User ID:', 'shaplatools' ),
			),
			'flickr_id_desc' => array(
				'type' => 'description',
				'std'  => sprintf(
					__( 'Head over to %s to find your Flickr user ID.', 'shaplatools' ),
					'<a href="//idgettr.com" target="_blank" rel="nofollow">idgettr</a>'
				),
			),
			'flickr_count'   => array(
				'type'  => 'number',
				'std'   => 9,
				'label' => __( 'Number of photos to show:', 'shaplatools' ),
				'step'  => 1,
				'min'   => 1,
				'max'   => 20,
			),
		);
		parent::__construct();
	}


	/**
	 * Echoes the widget content.
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {

		if ( $this->get_cached_widget( $args ) ) {
			return;
		}

		ob_start();

		$title        = apply_filters( 'widget_title', $instance['title'] );
		$flickr_id    = esc_attr( $instance['flickr_id'] );
		$flickr_count = absint( $instance['flickr_count'] );

		$items = $this->get_public_feed( $flickr_id, $flickr_count );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>

        <div class='shapla-flickr-widget'>
            <div class="shapla-flickr-row">
				<?php
				if ( isset( $items ) ) {
					foreach ( $items as $item ) {
						echo '<div class="shapla-flickr-col"><a target="_blank" href="' . $item['permalink'] . '"><img src="' . $item['src'] . '" alt="' . $item['alt'] . '"></a></div>';
					}
				}
				?>
            </div>
        </div>
		<?php
		echo $args['after_widget'];

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}

	/**
	 * Get flickr public feed by user id
	 *
	 * @param $user_id
	 * @param int $per_page
	 *
	 * @return array|bool
	 */
	private function get_public_feed( $user_id, $per_page = 20 ) {

		$expiration     = 15 * MINUTE_IN_SECONDS;
		$transient_name = 'shaplatools_flickr_public_feeds';

		if ( false === ( $data = get_transient( $transient_name ) ) ) {

			include_once( ABSPATH . WPINC . '/feed.php' );

			$base_url = 'http://api.flickr.com/services/feeds/photos_public.gne';
			$url      = add_query_arg( array(
				'ids'    => $user_id,
				'lang'   => 'en-us',
				'format' => 'rss_200',
			), $base_url );

			$rss = fetch_feed( $url );

			if ( is_wp_error( $rss ) ) {
				return false;
			}

			// Figure out how many total items there are.
			$max_items = $rss->get_item_quantity( $per_page );

			// Build an array of all the items,
			// starting with element 0 (first element).
			$items = $rss->get_items( 0, $max_items );

			$data = array();

			$i = 0;
			foreach ( $items as $item ) {
				$image_group = $item->get_item_tags( 'http://search.yahoo.com/mrss/', 'thumbnail' );
				$image_attrs = $image_group[0]['attribs'];
				foreach ( $image_attrs as $image ) {

					$_img_src = $image['url'];
					$_img_src = str_replace( 'http://', 'https://', $_img_src );

					$data[ $i ]['alt']       = esc_attr( $item->get_title() );
					$data[ $i ]['src']       = esc_url( $_img_src );
					$data[ $i ]['permalink'] = esc_url( $item->get_permalink() );
				}

				$i ++;
			}

			set_transient( $transient_name, $data, $expiration );
		}

		return $data;
	}

	public static function register() {
		register_widget( __CLASS__ );
	}
}

add_action( 'widgets_init', array( 'Shapla_Flickr_Widget', 'register' ) );
