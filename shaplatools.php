<?php
/**
 * Plugin Name:       ShaplaTools
 * Plugin URI:        https://wordpress.org/plugins/shaplatools/
 * Description:       ShaplaTools is a powerful plugin to extend functionality to your WordPress themes.
 * Version:           1.4.0
 * Author:            Sayful Islam
 * Author URI:        https://sayfulit.com
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       shaplatools
 * Domain Path:       /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'ShaplaTools' ) ) {
	/**
	 * Main ShaplaTools Class
	 *
	 * @package ShaplaTools
	 * @author Sayful Islam
	 * @link https://sayfulislam.com
	 */
	class ShaplaTools {
		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name
		 */
		private $plugin_name = 'shaplatools';

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version
		 */
		private $version = '1.4.0';

		/**
		 * The single instance of the class.
		 *
		 * @var ShaplaTools
		 */
		protected static $instance = null;

		/**
		 * Main ShaplaTools Instance
		 *
		 * Ensures only one instance of ShaplaTools is loaded or can be loaded.
		 *
		 * @since 1.2.0
		 * @static
		 * @see ShaplaTools()
		 * @return ShaplaTools - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * ShaplaTools constructor.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {

			do_action( 'shaplatools_init' );

			// Define plugin constants
			$this->define_constants();

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

			// links to display on the plugins page
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'after_setup_theme', array( $this, 'editor_styles' ) );

			register_activation_hook( __FILE__, array( $this, 'shaplatools_activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'shaplatools_deactivation' ) );

			// Include required files
			$this->includes();

			add_action( 'init', array( &$this, 'init' ), 1 );

			do_action( 'shaplatools_loaded' );
		}


		/**
		 * Define plugin constants
		 */
		private function define_constants() {
			define( 'SHAPLATOOLS_VERSION', $this->version );
			define( 'SHAPLATOOLS_FILE', __FILE__ );
			define( 'SHAPLATOOLS_PATH', dirname( SHAPLATOOLS_FILE ) );
			define( 'SHAPLATOOLS_INCLUDES', SHAPLATOOLS_PATH . '/includes' );
			define( 'SHAPLATOOLS_TEMPLATES', SHAPLATOOLS_PATH . '/templates' );
			define( 'SHAPLATOOLS_WIDGETS', SHAPLATOOLS_INCLUDES . '/widgets' );
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

		public function init() {
			$theme_supports = get_theme_support( 'shaplatools' );

			if ( false === $theme_supports ) {
				return;
			}

			if ( in_array( 'slider', $theme_supports[0] ) ) {
				include_once SHAPLATOOLS_INCLUDES . '/post-type/class-shaplatools-slide.php';
				include_once SHAPLATOOLS_INCLUDES . '/meta-box/class-shaplatools-nivoslide-metabox.php';
			}

			if ( in_array( 'portfolio', $theme_supports[0] ) ) {
				include_once SHAPLATOOLS_INCLUDES . '/post-type/class-shaplatools-portfolio.php';
				include_once SHAPLATOOLS_INCLUDES . '/meta-box/class-shaplatools-portfolio-metabox.php';
			}
		}

		private function includes() {
			// Include Libraries
			include_once SHAPLATOOLS_INCLUDES . '/lib/class-shaplatools-settings-api.php';
			include_once SHAPLATOOLS_INCLUDES . '/lib/class-shaplatools-meta-box.php';
			include_once SHAPLATOOLS_INCLUDES . '/lib/class-shaplatools-twitter-api.php';
			include_once SHAPLATOOLS_INCLUDES . '/lib/class-shaplatools-widget.php';

			// Include plugin settings file
			include_once SHAPLATOOLS_INCLUDES . '/settings/settings.php';

			// Include widgets
			include_once SHAPLATOOLS_WIDGETS . '/widget-twitter-feed.php';
			include_once SHAPLATOOLS_WIDGETS . '/widget-facebook-like-box.php';
			include_once SHAPLATOOLS_WIDGETS . '/widget-flickr.php';
			include_once SHAPLATOOLS_WIDGETS . '/widget-instagram.php';
			include_once SHAPLATOOLS_WIDGETS . '/widget-dribbble.php';

			// Include shortcode files
			include_once SHAPLATOOLS_PATH . '/shortcodes/class-shaplatools-post-types-shortcode.php';
			include_once SHAPLATOOLS_PATH . '/shortcodes/class-shaplatools-grid-shortcode.php';
			include_once SHAPLATOOLS_PATH . '/shortcodes/class-shaplatools-components-shortcode.php';

			$this->include_shortcodes();
			$this->include_tinymce_shortcodes();
		}

		public function include_shortcodes() {
			new ShaplaTools_Grid_Shortcode();
			new ShaplaTools_Post_Types_Shortcode();
			new Shaplatools_Components_Shortcode();
		}

		public function include_tinymce_shortcodes() {
			if ( is_admin() ) {
				include_once SHAPLATOOLS_INCLUDES . '/tiny-mce/ShaplaTools_TinyMCE.php';
				include_once SHAPLATOOLS_INCLUDES . '/tiny-mce/shapla-shortcodes.php';

				new ShaplaTools_TinyMCE();
				new ShaplaShortcodes();
			}
		}

		/**
		 * Whether the passed content contains the specified shortcode
		 *
		 * @param string $shortcode
		 *
		 * @return bool
		 */
		public function has_shortcode( $shortcode ) {
			global $post;
			if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $shortcode ) ) {
				return true;
			}

			return false;
		}

		public function enqueue_scripts() {
			wp_enqueue_style( 'font-awesome', SHAPLATOOLS_ASSETS . '/css/font-awesome.min.css', '', '4.7.0', 'all' );
			wp_enqueue_style( 'shaplatools', SHAPLATOOLS_ASSETS . '/css/style.css', '', $this->version, 'all' );
			wp_register_script( 'shapla-shortcode-scripts', SHAPLATOOLS_ASSETS . '/js/shapla-shortcode-scripts.js', array(
				'jquery',
				'jquery-ui-accordion',
				'jquery-ui-tabs'
			), $this->version, true );

			wp_register_script( 'retinajs', SHAPLATOOLS_ASSETS . '/library/retina.min.js', array(), '2.1.0', true );
			wp_register_script( 'owl-carousel', SHAPLATOOLS_ASSETS . '/library/owl.carousel.min.js', array( 'jquery' ), '2.0.0', true );
			wp_register_script( 'nivo-slider', SHAPLATOOLS_ASSETS . '/library/nivo-slider.min.js', array( 'jquery' ), '3.2.0', true );
			wp_register_script( 'typeahead', SHAPLATOOLS_ASSETS . '/library/typeahead.min.js', array( 'jquery' ), $this->version, true );
			wp_register_script( 'shuffle', SHAPLATOOLS_ASSETS . '/library/shuffle.min.js', array( 'jquery' ), $this->version, true );

			if ( $this->has_shortcode( 'shapla_slide' ) ) {
				wp_enqueue_script( 'nivo-slider' );
			}

			if ( $this->has_shortcode( 'shapla_portfolio' ) ) {
				wp_enqueue_script( 'shuffle' );
			}

			wp_localize_script( 'jquery', 'shaplatools', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'shaplatools_nonce' ),
			) );
		}

		public function admin_scripts( $hook ) {
			global $post;
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_media();

			wp_enqueue_style( 'shaplatools-admin', SHAPLATOOLS_ASSETS . '/css/admin-style.css', array(), $this->version, 'all' );
			wp_enqueue_script( 'shaplatools-media-gallery', SHAPLATOOLS_ASSETS . '/js/admin-media-gallery.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( 'jquery', 'shaplatools', array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'shaplatools_nonce' ),
				'post_id'   => $post ? $post->ID : '',
				'image_ids' => $post ? get_post_meta( $post->ID, '_shaplatools_images_ids', true ) : '',
			) );

			if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {

				wp_enqueue_style( 'font-awesome', SHAPLATOOLS_ASSETS . '/css/font-awesome.min.css', '', '4.7.0', 'all' );

				wp_register_script( 'font-awesome-icons-list', SHAPLATOOLS_ASSETS . '/js/icons.js', array(), false, true );
				wp_enqueue_script( 'font-awesome-icons-list' );

				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'shapla-shortcode-plugins', SHAPLATOOLS_ASSETS . '/js/shortcodes_plugins.js', array( 'font-awesome-icons-list' ), $this->version, true );

				wp_localize_script( 'jquery', 'ShaplaShortcodes', array(
					'plugin_folder'           => WP_PLUGIN_URL . '/shortcodes',
					'media_frame_video_title' => __( 'Upload or Choose Your Custom Video File', 'shaplatools' ),
					'media_frame_image_title' => __( 'Upload or Choose Your Custom Image File', 'shaplatools' )
				) );
			}
		}

		/**
		 * Editor styles to hook in WordPress editor.
		 *
		 * @return void
		 */
		public function editor_styles() {
			$editor_style = SHAPLATOOLS_ASSETS . '/css/editor-style.css';
			add_editor_style( $editor_style );
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
		public function shaplatools_activation() {
			do_action( 'shaplatools_activation' );
			flush_rewrite_rules();
		}

		/**
		 * Flush the rewrite rules on deactivation
		 */
		public function shaplatools_deactivation() {
			do_action( 'shaplatools_deactivation' );
			flush_rewrite_rules();
		}
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

// Global for backwards compatibility.
$GLOBALS['shaplatools'] = shaplatools();