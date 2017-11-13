<?php

namespace ShaplaTools\Modules\Retina2x;


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
			'retinajs',
			SHAPLATOOLS_ASSETS . '/library/retina.min.js',
			array(),
			'2.1.0',
			true
		);
	}

	/**
	 * Check if it should load frontend scripts
	 *
	 * @return boolean
	 */
	private function should_load_scripts() {
		$load_scripts = true;

		return apply_filters( 'shaplatools_retina2x_load_scripts', $load_scripts );
	}

}

Script::init();
