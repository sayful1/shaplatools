<?php

class Shapla_Tweet_Widget extends ShaplaTools_Widget {

	/**
	 * Shapla_Tweet_Widget constructor.
	 */
	public function __construct() {
		$this->widget_id          = 'shapla-latest-tweets';
		$this->widget_css_class   = 'widget_shapla_tweets';
		$this->widget_description = __( 'Displays your latest tweets from Twitter.', 'shaplatools' );
		$this->widget_name        = __( 'Shapla Twitter Feed', 'shaplatools' );
		$this->settings           = array(
			'title'                     => array(
				'type'  => 'text',
				'std'   => 'Tweets',
				'label' => __( 'Title:', 'shaplatools' ),
			),
			'update_count'              => array(
				'type'  => 'number',
				'std'   => 5,
				'label' => __( 'Number of Tweets to Display:', 'shaplatools' ),
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

		$title                     = $instance['title'];
		$limit                     = $instance['update_count'];
		$oauth_access_token        = $instance['oauth_access_token'];
		$oauth_access_token_secret = $instance['oauth_access_token_secret'];
		$consumer_key              = $instance['consumer_key'];
		$consumer_secret           = $instance['consumer_secret'];

		ob_start();

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}


		// Get the tweets.
		$tweets = $this->get_tweets( $limit, $oauth_access_token, $oauth_access_token_secret, $consumer_key, $consumer_secret );

		if ( $tweets ) {
			// Add links to URL and username mention in tweets.
			$patterns = array( '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '/@([A-Za-z0-9_]{1,15})/' );
			$replace  = array( '<a href="$1">$1</a>', '<a href="http://twitter.com/$1">@$1</a>' );

			echo '<ul class="tweets">';
			foreach ( $tweets as $tweet ) {

				$result     = preg_replace( $patterns, $replace, $tweet->text );
				$tweet_time = sprintf( __( '%s ago', 'shaplatools' ), human_time_diff( strtotime( $tweet->created_at ) ) );

				echo '<li class="tweet">';
				echo $result;
				echo '<span class="tweet-time">' . $tweet_time . '</span>';
				echo '</li>';
			}
			echo '</ul>';

		} else {
			if ( current_user_can( 'manage_options' ) ) {
				esc_html_e( 'Error fetching twitter feeds. Please verify the Twitter settings in the widget.', 'display-latest-tweets' );
			}
		}

		echo $args['after_widget'];

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}

	/**
	 * Returns a collection of the most recent Tweets posted by the user
	 *
	 * @param $limit
	 * @param $oauth_access_token
	 * @param $oauth_access_token_secret
	 * @param $consumer_key
	 * @param $consumer_secret
	 *
	 * @return array|mixed|object
	 */
	private function get_tweets( $limit, $oauth_access_token, $oauth_access_token_secret, $consumer_key, $consumer_secret ) {
		/** Set access tokens here - see: https://dev.twitter.com/apps/ */
		$settings = array(
			'oauth_access_token'        => $oauth_access_token,
			'oauth_access_token_secret' => $oauth_access_token_secret,
			'consumer_key'              => $consumer_key,
			'consumer_secret'           => $consumer_secret
		);

		$expiration     = 15 * MINUTE_IN_SECONDS;
		$transient_name = 'shaplatools_twitter_feeds_' . $limit . '_' . $expiration;

		if ( false === ( $tweets = get_transient( $transient_name ) ) ) {

			$url            = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
			$getField       = '?count=' . $limit;
			$request_method = 'GET';

			if ( ! class_exists( 'ShaplaTools_Twitter_API' ) ) {
				require_once 'shaplatools-twitter-api.php';
			}
			$twitter_instance = new ShaplaTools_Twitter_API( $settings );

			$query = $twitter_instance
				->set_get_field( $getField )
				->build_oauth( $url, $request_method )
				->process_request();

			$tweets = json_decode( $query );

			set_transient( $transient_name, $tweets, $expiration );
		}

		return $tweets;
	}

	public static function register() {
		register_widget( __CLASS__ );
	}
}

add_action( 'widgets_init', array( 'Shapla_Tweet_Widget', 'register' ) );
