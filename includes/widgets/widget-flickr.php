<?php

class Shapla_Widget_Flickr extends ShaplaTools_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		$this->widget_id          = 'shapla-flickr';
		$this->widget_cssclass    = 'widget_shapla_flickr';
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
				'std'  => sprintf( __( 'Head over to %s to find your Flickr user ID.', 'shaplatools' ), '<a href="//idgettr.com" target="_blank" rel="nofollow">idgettr</a>' ),
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
	 * Display the widget content.
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {

		if ( $this->get_cached_widget( $args ) ) {
			return;
		}

		ob_start();

		$title        = isset( $instance['title'] ) ? esc_html( $instance['title'] ) : null;
		$flickr_id    = isset( $instance['flickr_id'] ) ? esc_attr( $instance['flickr_id'] ) : null;
		$flickr_count = isset( $instance['flickr_count'] ) ? absint( $instance['flickr_count'] ) : 9;

		$feeds = $this->flickr_public_feed( $flickr_id, $flickr_count );

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>

        <div class='shapla-flickr-widget'>
            <div class="shapla-flickr-row">
				<?php
				foreach ( $feeds as $feed ) {
					$html = '<div class="shapla-flickr-col">';
					$html .= '<a target="_blank" href="' . $feed['permalink'] . '">';
					$html .= '<img src="' . $feed['src'] . '" alt="' . $feed['alt'] . '">';
					$html .= '</a>';
					$html .= '</div>';

					echo $html;
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
	private function flickr_public_feed( $user_id, $per_page = 20 ) {

		include_once( ABSPATH . WPINC . '/feed.php' );

		$url = add_query_arg( array(
			'ids'    => $user_id,
			'lang'   => 'en-us',
			'format' => 'rss_200',
		), 'https://api.flickr.com/services/feeds/photos_public.gne' );

		/** @var \WP_Error|\SimplePie $rss */
		$rss = fetch_feed( $url );

		if ( is_wp_error( $rss ) ) {
			return false;
		}

		// Figure out how many total items there are.
		$max_items = $rss->get_item_quantity( $per_page );
		// Build an array of all the items,
		// starting with element 0 (first element).
		$items = $rss->get_items( 0, $max_items );
		$data  = array();
		$i     = 0;
		/** @var \SimplePie_Item $item */
		foreach ( $items as $item ) {
			$image_group = $item->get_item_tags( 'http://search.yahoo.com/mrss/', 'thumbnail' );
			$image_attrs = $image_group[0]['attribs'];
			foreach ( $image_attrs as $image ) {
				$_img_src                = $image['url'];
				$_img_src                = str_replace( 'http://', 'https://', $_img_src );
				$data[ $i ]['width']     = $image['width'];
				$data[ $i ]['height']    = $image['height'];
				$data[ $i ]['alt']       = esc_attr( $item->get_title() );
				$data[ $i ]['src']       = esc_url_raw( $_img_src );
				$data[ $i ]['permalink'] = esc_url_raw( $item->get_permalink() );
			}
			$i ++;
		}

		return $data;
	}

	/**
	 * Register current class as Widget
	 */
	public static function register() {
		register_widget( __CLASS__ );
	}
}

add_action( 'widgets_init', array( 'Shapla_Widget_Flickr', 'register' ) );
