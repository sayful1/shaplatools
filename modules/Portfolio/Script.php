<?php

namespace ShaplaTools\Modules\Portfolio;


class Script {

	private static $instance = null;

	/**
	 * @return Script
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Script constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Load scripts for slider
	 */
	public function enqueue_scripts() {
		if ( ! $this->should_load_scripts() ) {
			return;
		}

		wp_enqueue_script(
			'shuffle',
			SHAPLATOOLS_PORTFOLIO_ASSETS . '/js/shuffle.min.js',
			array( 'jquery' ),
			'3.1.1',
			true
		);
	}

	/**
	 * Check if it should load frontend scripts
	 *
	 * @return boolean
	 */
	private function should_load_scripts() {
		global $post;
		$load_scripts = is_active_widget( false, false, 'widget_shapla_portfolio', true ) ||
		                ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'shapla_portfolio' ) );

		return apply_filters( 'shaplatools_portfolio_load_scripts', $load_scripts );
	}
}

Script::init();
