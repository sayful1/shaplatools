<?php
/**
 * Plugin Name:       ShaplaTools
 * Plugin URI:        https://wordpress.org/plugins/shaplatools/
 * Description:       ShaplaTools is a powerful plugin to extend functionality to your WordPress themes.
 * Version:           1.4.0
 * Author:            Sayful Islam
 * Author URI:        https://sayfulislam.com
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       shaplatools
 * Domain Path:       /languages
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Main ShaplaTools Class
 *
 * @package ShaplaTools
 * @author Sayful Islam
 * @link https://sayfulislam.com
 */
final class ShaplaTools {
	/**
	 * The unique identifier of this plugin.
	 *
	 * @var string
	 */
	private $plugin_name = 'shaplatools';

	/**
	 * The current version of the plugin.
	 *
	 * @var string
	 */
	private $version = '1.4.0';

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Main ShaplaTools Instance
	 *
	 * Ensures only one instance of ShaplaTools is loaded or can be loaded.
	 *
	 * @return ShaplaTools - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			do_action( 'shaplatools_init' );

			// Define plugin constants
			self::$instance->define_constants();

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			// links to display on the plugins page
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ self::$instance, 'action_links' ] );

			register_activation_hook( __FILE__, array( self::$instance, 'activation' ) );
			register_deactivation_hook( __FILE__, array( self::$instance, 'deactivation' ) );

			do_action( 'shaplatools_loaded' );
		}

		return self::$instance;
	}

	/**
	 * Define plugin constants
	 */
	private function define_constants() {
		define( 'SHAPLATOOLS_VERSION', $this->version );
		define( 'SHAPLATOOLS_FILE', __FILE__ );
		define( 'SHAPLATOOLS_PATH', dirname( SHAPLATOOLS_FILE ) );
		define( 'SHAPLATOOLS_INCLUDES', SHAPLATOOLS_PATH . '/includes' );
		define( 'SHAPLATOOLS_MODULES', SHAPLATOOLS_PATH . '/modules' );
		define( 'SHAPLATOOLS_URL', plugins_url( '', SHAPLATOOLS_FILE ) );
		define( 'SHAPLATOOLS_ASSETS', SHAPLATOOLS_URL . '/assets' );
	}

	/**
	 * Load a .mo file into the text domain $domain.
	 *
	 * @return void
	 */
	function load_textdomain() {
		// Set filter for plugin's languages directory
		$shaplatools_lang_dir = SHAPLATOOLS_PATH . '/languages/';
		$shaplatools_lang_dir = apply_filters( 'shaplatools_languages_directory', $shaplatools_lang_dir );

		// Traditional WordPress plugin locale filter
		$locale  = apply_filters( 'plugin_locale', get_locale(), 'shaplatools' );
		$mo_file = sprintf( '%1$s-%2$s.mo', 'shaplatools', $locale );

		// Setup paths to current locale file
		$mo_file_local  = $shaplatools_lang_dir . $mo_file;
		$mo_file_global = WP_LANG_DIR . '/shaplatools/' . $mo_file;

		if ( file_exists( $mo_file_global ) ) {
			// Look in global /wp-content/languages/shaplatools folder
			load_textdomain( $this->plugin_name, $mo_file_global );
		} elseif ( file_exists( $mo_file_local ) ) {
			// Look in local /wp-content/plugins/shaplatools/languages/ folder
			load_textdomain( $this->plugin_name, $mo_file_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( $this->plugin_name, false, $shaplatools_lang_dir );
		}
	}

	/**
	 * Add custom links on plugins page.
	 *
	 * @access public
	 *
	 * @param mixed $links
	 *
	 * @return array
	 */
	public function action_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'options-general.php?page=shaplatools' ) . '">' . __( 'Settings', 'shaplatools' ) . '</a>'
		);

		return array_merge( $plugin_links, $links );
	}

	/**
	 * Flush the rewrite rules on activation
	 */
	public function activation() {
		do_action( 'shaplatools_activation' );
		flush_rewrite_rules();
	}

	/**
	 * Flush the rewrite rules on deactivation
	 */
	public function deactivation() {
		do_action( 'shaplatools_deactivation' );
		flush_rewrite_rules();
	}
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function shaplatools() {
	return ShaplaTools::instance();
}

shaplatools();
