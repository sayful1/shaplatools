<?php

namespace ShaplaTools\Modules\Search;


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

		wp_enqueue_style(
			'shaplatools-search',
			SHAPLATOOLS_SEARCH_ASSETS . '/css/search.css',
			array(),
			SHAPLATOOLS_VERSION,
			'all'
		);

		wp_enqueue_script(
			'jquery-autocomplete',
			SHAPLATOOLS_SEARCH_ASSETS . '/js/jquery.autocomplete.min.js',
			array( 'jquery' ),
			'1.4.4',
			true
		);

		wp_enqueue_script(
			'shaplatools-search',
			SHAPLATOOLS_SEARCH_ASSETS . '/js/search.js',
			array( 'jquery', 'jquery-autocomplete' ),
			SHAPLATOOLS_VERSION,
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

		return apply_filters( 'shaplatools_search_load_scripts', $load_scripts );
	}
}

Script::init();
