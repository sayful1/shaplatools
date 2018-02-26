<?php

/**
 * Display your latest Dribbble shots.
 */
class Shapla_Dribbble_Widget extends ShaplaTools_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		$this->widget_id          = 'shapla-dribbble';
		$this->widget_cssclass    = 'widget_shapla_dribbble';
		$this->widget_description = __( 'Display your latest Dribbble shots.', 'shaplatools' );
		$this->widget_name        = __( 'Shapla Dribbble Shots', 'shaplatools' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => 'Latest Shots',
				'label' => __( 'Title:', 'shaplatools' ),
			),
			'count' => array(
				'type'  => 'number',
				'std'   => 4,
				'label' => __( 'Number of shots to show:', 'shaplatools' ),
				'step'  => 1,
				'min'   => 1,
				'max'   => 10,
			),
		);

		parent::__construct();
	}

	function widget( $args, $instance ) {
		if ( $this->get_cached_widget( $args ) ) {
			return;
		}

		ob_start();

		$title = apply_filters( 'widget_title', $instance['title'] );
		$count = absint( $instance['count'] );
		$index = 0;

		/* Display the widget title if one was input (before and after defined by themes). */
		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$options = get_option( 'shaplatools_options' );
		if ( empty( $options['dribbble_access_token'] ) ) {
			if ( current_user_can( 'edit_theme_options' ) ) {
				?>
                <p class="shapla-alert shapla-alert--red">
					<?php
					echo sprintf(
						__( 'Please generate an access token from <a href="">ShaplaTools settings</a>', 'shaplatools' ),
						admin_url( 'options-general.php?page=shaplatools' )
					);
					?>
                </p>
				<?php
			}

			return;
		}

		$shots = $this->dribbble_shots( $options['dribbble_access_token'], $count );
		?>
        <ul class="dribbbles">
			<?php if ( ! empty( $shots ) ) : ?>
				<?php foreach ( $shots as $shot ) : ?>
                    <li class="dribbble-shot">
                        <a href="<?php echo esc_url( $shot->html_url ); ?>" class="dribbble-link"
                           title="<?php echo esc_attr( $shot->title ); ?>">
                            <img src="<?php echo esc_url( $shot->images->normal ); ?>"
                                 srcset="<?php echo esc_url( $shot->images->normal ); ?> 1x, <?php echo esc_url( $shot->images->hidpi ); ?> 2x"
                                 alt="<?php echo esc_attr( $shot->title ); ?>"
                                 width="<?php echo esc_attr( $shot->width ); ?>"
                                 height="<?php echo esc_attr( $shot->height ); ?>">
                        </a>
                    </li>
					<?php
					$index ++;
					if ( $index === $count ) {
						break;
					}
					?>
				<?php endforeach; ?>
			<?php endif; ?>
        </ul>

		<?php
		echo $args['after_widget'];

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}

	/**
	 * Get Dribbble shots.
	 *
	 * @param string $username Dribbble username.
	 * @param string $access_token Client access token.
	 * @param int $count Number of posts to return.
	 *
	 * @since 2.2.0.
	 *
	 * @return mixed
	 */
	public function dribbble_shots( $access_token, $count ) {
		if ( '' === $access_token ) {
			return false;
		}

		$transient_key = "shaplatools_dribble_${access_token}_${count}";
		$shots         = get_transient( $transient_key );

		if ( empty( $shots ) || false === $shots ) {
			$remote_url = add_query_arg( array(
				'access_token' => $access_token,
				'per_page'     => $count,
			), 'https://api.dribbble.com/v2/user/shots' );

			$request = wp_remote_get( $remote_url, array(
				'sslverify' => false,
			) );

			if ( is_wp_error( $request ) ) {
				return false;
			} else {
				$body  = wp_remote_retrieve_body( $request );
				$shots = json_decode( $body );

				if ( ! empty( $shots ) ) {
					set_transient( $transient_key, $shots, DAY_IN_SECONDS );
				}
			}
		}

		return $shots;
	}

	public static function register() {
		register_widget( __CLASS__ );
	}
}

add_action( 'widgets_init', array( 'Shapla_Dribbble_Widget', 'register' ) );
