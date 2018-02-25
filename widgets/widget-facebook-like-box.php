<?php

class Shapla_Widget_Facebook_Like_Box extends ShaplaTools_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		$this->widget_id          = 'shapla_fb_like_box';
		$this->widget_cssclass    = 'widget_shapla_facebook_like_box';
		$this->widget_description = __( 'Facebook Like Box only for Facebook Pages.', 'shaplatools' );
		$this->widget_name        = __( 'Shapla Facebook Like Box', 'shaplatools' );
		$this->settings           = array(
			'title'        => array(
				'type'  => 'text',
				'std'   => 'Find us on Facebook',
				'label' => __( 'Title:', 'shaplatools' ),
			),
			'app_id'       => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'App ID:', 'shaplatools' ),
			),
			'href'         => array(
				'type'        => 'text',
				'std'         => '',
				'label'       => __( 'Facebook Page URL:', 'shaplatools' ),
				'description' => __( 'The absolute URL of the Facebook Page that will be liked. e.g. https://www.facebook.com/FacebookDevelopers', 'shaplatools' ),
			),
			'width'        => array(
				'type'        => 'number',
				'std'         => 300,
				'label'       => __( 'Width:', 'shaplatools' ),
				'description' => __( 'The width of the plugin in pixels. Minimum is 180. Maximum is 500.', 'shaplatools' ),
				'step'        => 1,
				'min'         => 180,
				'max'         => 500,
			),
			'height'       => array(
				'type'        => 'number',
				'std'         => 300,
				'label'       => __( 'Height:', 'shaplatools' ),
				'description' => __( 'The height of the plugin in pixels. The default height varies based on number of faces to display, and whether the stream is displayed.', 'shaplatools' ),
				'step'        => 1,
				'min'         => 180,
				'max'         => 500,
			),
			'showfaces'    => array(
				'type'  => 'checkbox',
				'std'   => true,
				'label' => __( 'Show Friends\' Faces', 'shaplatools' ),
			),
			'stream'       => array(
				'type'  => 'checkbox',
				'std'   => true,
				'label' => __( 'Show Page Posts', 'shaplatools' ),
			),
			'small_header' => array(
				'type'  => 'checkbox',
				'std'   => false,
				'label' => __( 'Use Small Header', 'shaplatools' ),
			),
			'hide_cover'   => array(
				'type'  => 'checkbox',
				'std'   => false,
				'label' => __( 'Hide Cover Photo', 'shaplatools' ),
			)
		);

		parent::__construct();
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		/* Our variables from the widget settings. */
		$title         = apply_filters( 'widget_title', $instance['title'] );
		$app_id        = isset( $instance['app_id'] ) ? esc_attr( $instance['app_id'] ) : null;
		$href          = isset( $instance['href'] ) ? esc_url_raw( $instance['href'] ) : null;
		$width         = isset( $instance['width'] ) ? intval( $instance['width'] ) : 300;
		$height        = isset( $instance['height'] ) ? intval( $instance['height'] ) : 300;
		$show_facepile = ( $instance['showfaces'] == "1" ? "true" : "false" );
		$show_posts    = ( $instance['stream'] == "1" ? "true" : "false" );
		$small_header  = ( $instance['small_header'] == "1" ? "true" : "false" );
		$hide_cover    = ( $instance['hide_cover'] == "1" ? "true" : "false" );

		$options = get_option( 'shaplatools_options' );
		if ( ! empty( $options['facebook_app_id'] ) ) {
			$app_id = esc_attr( $options['facebook_app_id'] );
		}

		/* Display the widget title if one was input (before and after defined by themes). */
		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		/* Like Box */
		?>
        <div class="fb-page"
             data-href="<?php echo $href; ?>"
             data-width="<?php echo $width; ?>"
             data-height="<?php echo $height; ?>"
             data-small-header="<?php echo $small_header; ?>"
             data-adapt-container-width="true"
             data-hide-cover="<?php echo $hide_cover; ?>"
             data-show-facepile="<?php echo $show_facepile; ?>"
             data-show-posts="<?php echo $show_posts; ?>">
        </div>

        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=<?php echo $app_id; ?>";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
		<?php

		echo $args['after_widget'];
	}

	public static function register() {
		register_widget( __CLASS__ );
	}

}

// Register the Widget
add_action( 'widgets_init', array( 'Shapla_Widget_Facebook_Like_Box', 'register' ) );
