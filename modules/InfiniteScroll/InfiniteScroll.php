<?php

namespace ShaplaTools\Modules\InfiniteScroll;

class InfiniteScroll {

	private static $instance = null;

	/**
	 * @return InfiniteScroll
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
		define( 'SHAPLATOOLS_INFINITE_SCROLL_FILE', __FILE__ );
		define( 'SHAPLATOOLS_INFINITE_SCROLL_PATH', dirname( __FILE__ ) );
		define( 'SHAPLATOOLS_INFINITE_SCROLL_VIEWS', dirname( __FILE__ ) . '/views' );
		define( 'SHAPLATOOLS_INFINITE_SCROLL_ASSETS', plugins_url( '/assets', __FILE__ ) );
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

		include SHAPLATOOLS_INFINITE_SCROLL_PATH . '/Script.php';
	}

	/**
	 * Check if current module is enabled
	 *
	 * @return bool
	 */
	private function is_module_enabled() {
		if ( get_theme_support( 'shaplatools-infinite-scroll' ) ) {
			return true;
		}

		return true;
	}
}

InfiniteScroll::init();
