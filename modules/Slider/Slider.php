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

		if ( ! $this->is_module_enabled() ) {
			return;
		}

		include_once SHAPLATOOLS_SLIDER_PATH . '/PostType.php';
		include_once SHAPLATOOLS_SLIDER_PATH . '/MetaBox.php';
		include_once SHAPLATOOLS_SLIDER_PATH . '/ShortCode.php';
		include_once SHAPLATOOLS_SLIDER_PATH . '/Widget.php';
		include_once SHAPLATOOLS_SLIDER_PATH . '/Script.php';
	}

	/**
	 * Check if current module is enabled
	 *
	 * @return bool
	 */
	private function is_module_enabled() {
		if ( get_theme_support( 'shaplatools-slider' ) ) {
			return true;
		}

		$options      = get_option( 'shaplatools_options' );
		$shapla_slide = isset( $options['shapla_slide'] ) ? $options['shapla_slide'] : array();

		if ( in_array( 'slide_post_type', $shapla_slide ) ) {
			return true;
		}

		return false;
	}
}

Slider::init();
