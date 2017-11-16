<?php

namespace ShaplaTools\Modules\Search;

class Search {

	private $options;
	private static $instance = null;

	/**
	 * @return Search
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

		$this->options = get_option( 'shaplatools_options' );

		// Include required files
		add_action( 'init', array( $this, 'includes' ), 0 );
	}

	/**
	 * Define the plugin constants
	 *
	 * @return void
	 */
	private function define_constants() {
		define( 'SHAPLATOOLS_SEARCH_FILE', __FILE__ );
		define( 'SHAPLATOOLS_SEARCH_PATH', dirname( __FILE__ ) );
		define( 'SHAPLATOOLS_SEARCH_VIEWS', dirname( __FILE__ ) . '/views' );
		define( 'SHAPLATOOLS_SEARCH_ASSETS', plugins_url( '/assets', __FILE__ ) );
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

		include_once SHAPLATOOLS_SEARCH_PATH . '/Script.php';
		include_once SHAPLATOOLS_SEARCH_PATH . '/AjaxSearchResponse.php';
	}

	/**
	 * Check if current module is enabled
	 *
	 * @return bool
	 */
	private function is_module_enabled() {
		if ( get_theme_support( 'shaplatools-search' ) ) {
			return true;
		}

		return true;
	}

}

Search::init();
