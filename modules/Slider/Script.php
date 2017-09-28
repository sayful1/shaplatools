<?php

namespace ShaplaTools\Modules\Slider;


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
			'nivo-slider',
			SHAPLATOOLS_SLIDER_ASSETS . '/js/nivo-slider.min.js',
			array( 'jquery' ),
			'3.2.0',
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
		$load_scripts = is_active_widget( false, false, 'widget_shapla_slider', true ) ||
		                ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'shapla_slider' ) );

		return apply_filters( 'carousel_slider_load_scripts', $load_scripts );
	}
}

Script::init();
