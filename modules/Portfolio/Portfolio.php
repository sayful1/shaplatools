<?php

namespace ShaplaTools\Modules\Portfolio;

class Portfolio {

	private static $instance = null;

	/**
	 * @return Portfolio
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
		define( 'SHAPLATOOLS_PORTFOLIO_FILE', __FILE__ );
		define( 'SHAPLATOOLS_PORTFOLIO_PATH', dirname( __FILE__ ) );
		define( 'SHAPLATOOLS_PORTFOLIO_VIEWS', dirname( __FILE__ ) . '/views' );
		define( 'SHAPLATOOLS_PORTFOLIO_ASSETS', plugins_url( '/assets', __FILE__ ) );
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

		include SHAPLATOOLS_PORTFOLIO_PATH . '/PostType.php';
		include SHAPLATOOLS_PORTFOLIO_PATH . '/Taxonomy.php';
		include SHAPLATOOLS_PORTFOLIO_PATH . '/MetaBox.php';
		include SHAPLATOOLS_PORTFOLIO_PATH . '/ShortCode.php';
		include SHAPLATOOLS_PORTFOLIO_PATH . '/Script.php';
	}

	/**
	 * Check if current module is enabled
	 *
	 * @return bool
	 */
	private function is_module_enabled() {
		if ( get_theme_support( 'shaplatools-portfolio' ) ) {
			return true;
		}

		$options      = get_option( 'shaplatools_options' );
		$shapla_slide = isset( $options['shapla_portfolio'] ) ? $options['shapla_portfolio'] : array();

		if ( in_array( 'portfolio_post_type', $shapla_slide ) ) {
			return true;
		}

		return false;
	}

}

Portfolio::init();
