<?php

namespace ShaplaTools\Modules\Testimonial;


class Testimonial {


	private static $instance = null;

	/**
	 * @return Testimonial
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
		define( 'SHAPLATOOLS_TESTIMONIAL_FILE', __FILE__ );
		define( 'SHAPLATOOLS_TESTIMONIAL_PATH', dirname( __FILE__ ) );
		define( 'SHAPLATOOLS_TESTIMONIAL_VIEWS', dirname( __FILE__ ) . '/views' );
		define( 'SHAPLATOOLS_TESTIMONIAL_ASSETS', plugins_url( '/assets', __FILE__ ) );
	}

	/**
	 * Include the required files
	 *
	 * @return void
	 */
	public function includes() {

		if ( ! $this->is_slider_module_enabled() ) {
			return;
		}

		include SHAPLATOOLS_TESTIMONIAL_PATH . '/PostType.php';
		include SHAPLATOOLS_TESTIMONIAL_PATH . '/MetaBox.php';
		include SHAPLATOOLS_TESTIMONIAL_PATH . '/ShortCode.php';

	}

	/**
	 * Check if current module is enabled
	 *
	 * @return bool
	 */
	private function is_slider_module_enabled() {
		if ( get_theme_support( 'shaplatools-testimonial' ) ) {
			return true;
		}

		$options            = get_option( 'shaplatools_options' );
		$shapla_testimonial = isset( $options['shapla_testimonial'] ) ? $options['shapla_testimonial'] : array();

		if ( in_array( 'testimonial_post_type', $shapla_testimonial ) ) {
			return true;
		}

		return false;
	}
}

Testimonial::init();
