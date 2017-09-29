<?php

namespace ShaplaTools\Modules\Slider;

class Slider {

	private static $instance = null;

	/**
	 * @return Slider
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		add_action( 'shaplatools_activation', array( $this, 'includes' ), 0 );
		add_action( 'init', array( $this, 'includes' ), 0 );
	}

	/**
	 * Define the plugin constants
	 *
	 * @return void
	 */
	private function define_constants() {
		define( 'SHAPLATOOLS_SLIDER_FILE', __FILE__ );
		define( 'SHAPLATOOLS_SLIDER_PATH', dirname( __FILE__ ) );
		define( 'SHAPLATOOLS_SLIDER_VIEWS', dirname( __FILE__ ) . '/views' );
		define( 'SHAPLATOOLS_SLIDER_ASSETS', plugins_url( '/assets', __FILE__ ) );
	}

	/**
	 * Include the required files
	 *
	 * @return void
	 */
	public function includes() {

		$theme_supports = get_theme_support( 'shapla-slider' );

		if ( ! $theme_supports ) {
			return;
		}

		include_once SHAPLATOOLS_SLIDER_PATH . '/PostType.php';
		include_once SHAPLATOOLS_SLIDER_PATH . '/MetaBox.php';
		include_once SHAPLATOOLS_SLIDER_PATH . '/ShortCode.php';
		include_once SHAPLATOOLS_SLIDER_PATH . '/Widget.php';
		include_once SHAPLATOOLS_SLIDER_PATH . '/Script.php';
	}
}

Slider::init();
