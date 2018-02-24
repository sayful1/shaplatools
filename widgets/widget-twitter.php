<?php

class Shapla_Tweet_Widget extends ShaplaTools_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		$this->widget_id          = 'shapla-latest-tweets';
		$this->widget_cssclass    = 'widget_shapla_tweets';
		$this->widget_description = __( 'Display a list of a user&rsquo;s latest tweets.', 'shaplatools' );
		$this->widget_name        = __( 'Shapla Twitter Feed', 'shaplatools' );
		$this->settings           = array(
			'description'               => array(
				'type' => 'description',
				'std'  => sprintf(
					__( 'Don\'t know your Consumer Key, Consumer Secret, Access Token and Access Token Secret? %sClick here%s', 'shaplatools' ),
					'<a target="_blank" href="https://apps.twitter.com/">',
					'</a>'
				),
			),
			'title'                     => array(
				'type'  => 'text',
				'std'   => 'Tweets',
				'label' => __( 'Title:', 'shaplatools' ),
			),
			'twitter_username'          => array(
				'type'  => 'text',
				'std'   => null,
				'label' => __( 'Twitter Username:', 'shaplatools' ),
			),
			'update_count'              => array(
				'type'  => 'number',
				'std'   => 5,
				'label' => __( 'Number of Tweets to show:', 'shaplatools' ),
				'step'  => 1,
				'min'   => 1,
				'max'   => 50,
			),
			'twitter_duration'          => array(
				'type'    => 'select',
				'std'     => '60',
				'label'   => __( 'Load new Tweets every:', 'shaplatools' ),
				'options' => $this->twitter_duration(),
			),
			'follow_link_show'          => array(
				'type'  => 'checkbox',
				'std'   => false,
				'label' => __( 'Include link to twitter page?', 'shaplatools' ),
			),
			'follow_link_text'          => array(
				'type'  => 'text',
				'std'   => 'Follow on twitter',
				'label' => __( 'Link Text:', 'shaplatools' ),
			),
			'consumer_key'              => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Consumer Key:', 'shaplatools' ),
			),
			'consumer_secret'           => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Consumer Secret:', 'shaplatools' ),
			),
			'oauth_access_token'        => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Access Token:', 'shaplatools' ),
			),
			'oauth_access_token_secret' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Access Token Secret:', 'shaplatools' ),
			),
		);

		parent::__construct();

		add_action( 'save_post', array( $this, 'flush_widget_transient' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_transient' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_transient' ) );
	}

	/**
	 * Delete transient
	 */
	public function flush_widget_transient() {
		delete_transient( $this->widget_id );
	}

	public function update( $new_instance, $old_instance ) {

		parent::update( $new_instance, $old_instance );

		$this->flush_widget_transient();

		return $new_instance;
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
		if ( $this->get_cached_widget( $args ) ) {
			return;
		}

		ob_start();

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];
		}

		// retrieve cache contents on success
		$settings         = array(
			'oauth_access_token'        => isset( $instance['oauth_access_token'] ) ? $instance['oauth_access_token'] : null,
			'oauth_access_token_secret' => isset( $instance['oauth_access_token_secret'] ) ? $instance['oauth_access_token_secret'] : null,
			'consumer_key'              => isset( $instance['consumer_key'] ) ? $instance['consumer_key'] : null,
			'consumer_secret'           => isset( $instance['consumer_secret'] ) ? $instance['consumer_secret'] : null,
		);
		$limit            = isset( $instance['update_count'] ) ? intval( $instance['update_count'] ) : 5;
		$twitter_duration = isset( $instance['twitter_duration'] ) ? intval( $instance['twitter_duration'] ) : 15;
		$username         = $instance['twitter_username'];

		// Get the tweets.
		$tweets = $this->twitter_timeline( $settings, $limit, $twitter_duration );


		if ( ! empty( $tweets ) ) {
			// Add links to URL and username mention in tweets.
			$patterns = array(
				'@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@',
				'/@([A-Za-z0-9_]{1,15})/'
			);
			$replace  = array( '<a href="$1">$1</a>', '<a href="http://twitter.com/$1">@$1</a>' );

			echo '<ul class="shapla-tweets">';
			foreach ( $tweets as $tweet ) {
				$text       = preg_replace( $patterns, $replace, $tweet['text'] );
				$created    = strtotime( $tweet['time'] );
				$human_time = human_time_diff( $created ) . esc_html__( ' ago', 'shaplatools' );

				echo '<li>' . $text . '<span>' . $human_time . '</span></li>';
			}
			echo '</ul>';

			if ( $instance['follow_link_show'] && $instance['follow_link_text'] && $username ) {
				echo '<a href="' . esc_url( 'https://twitter.com/' . $username ) . '" class="shapla-button twitter-follow-button" target="_blank">' . esc_html( $instance['follow_link_text'] ) . '</a>';
			}
		} else {
			if ( current_user_can( 'manage_options' ) ) {
				esc_html_e( 'Error fetching twitter feeds. Please verify the Twitter settings in the widget.', 'shapla' );
			}
		}

		echo $args['after_widget'];

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}

	/**
	 * transient duration
	 *
	 * @return array
	 */
	private function twitter_duration() {
		return array(
			'5'    => __( '5 Minutes', 'shaplatools' ),
			'15'   => __( '15 Minutes', 'shaplatools' ),
			'30'   => __( '30 Minutes', 'shaplatools' ),
			'60'   => __( '1 Hour', 'shaplatools' ),
			'120'  => __( '2 Hours', 'shaplatools' ),
			'240'  => __( '4 Hours', 'shaplatools' ),
			'720'  => __( '12 Hours', 'shaplatools' ),
			'1440' => __( '24 Hours', 'shaplatools' ),
		);
	}

	/**
	 * Making request to Twitter API
	 *
	 * @param array $settings
	 * @param int $limit
	 * @param int $twitter_duration
	 *
	 * @return array|mixed
	 */
	private function twitter_timeline( $settings, $limit = 5, $twitter_duration = 15 ) {
		// Do we have this information in our transients already?
		$tweets = get_transient( $this->widget_id );

		if ( false === $tweets ) {
			$twitter_instance = new ShaplaTools_Twitter_API( $settings );
			$timeline         = (array) $twitter_instance->user_timeline( $limit );

			foreach ( $timeline as $tweet ) {
				$tweets[] = array(
					'text' => $tweet->text,
					'time' => $tweet->created_at,
				);
			}

			$transient_expiration = ( intval( $twitter_duration ) * MINUTE_IN_SECONDS );
			set_transient( $this->widget_id, $tweets, $transient_expiration );
		}

		return $tweets;
	}

	/**
	 * Register current class as widget
	 */
	public static function register() {
		register_widget( __CLASS__ );
	}
}

add_action( 'widgets_init', array( 'Shapla_Tweet_Widget', 'register' ) );
