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

		include_once( ABSPATH . WPINC . '/feed.php' );

		$rss = fetch_feed( 'http://api.flickr.com/services/feeds/photos_public.gne?ids=' . $flickr_id . '&lang=en-us&format=rss_200' );
		add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 1800;' ) );

		if ( ! is_wp_error( $rss ) ) {
			$items = $rss->get_items( 0, $rss->get_item_quantity( $flickr_count ) );
		}

		echo $args['before_widget'];

		?>

        <div class='shapla-flickr-widget'>
			<?php if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>
            <div class="shapla-flickr-row">
				<?php
				if ( isset( $items ) ) {
					foreach ( $items as $item ) {
						$image_group = $item->get_item_tags( 'http://search.yahoo.com/mrss/', 'thumbnail' );
						$image_attrs = $image_group[0]['attribs'];
						foreach ( $image_attrs as $image ) {
							$url    = $image['url'];
							$width  = $image['width'];
							$height = $image['height'];
							echo '<div class="shapla-flickr-col"><a target="_blank" href="' . $item->get_permalink() . '"><img src="' . $url . '" width="' . $width . '" height="' . $height . '" alt="' . $item->get_title() . '"></a></div>';
						}
					}
				} else {
					_e( 'Invalid flickr ID', 'shaplatools' );
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

	public static function register() {
		register_widget( __CLASS__ );
	}
}

add_action( 'widgets_init', array( 'Shapla_Flickr_Widget', 'register' ) );
