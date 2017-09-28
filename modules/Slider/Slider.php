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
		$this->includes();
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
	private function includes() {
		include SHAPLATOOLS_SLIDER_PATH . '/PostType.php';
		include SHAPLATOOLS_SLIDER_PATH . '/MetaBox.php';
	}
}

Slider::init();
