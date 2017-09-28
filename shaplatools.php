<?php
/**
 * Plugin Name:       ShaplaTools
 * Plugin URI:        https://wordpress.org/plugins/shaplatools/
 * Description:       ShaplaTools is a powerful plugin to extend functionality to your WordPress themes. 
 * Version:           1.2.2
 * Author:            Sayful Islam
 * Author URI:        https://sayfulit.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       shapla
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'ShaplaTools' ) ) {

/**
 * Main ShaplaTools Class
 *
 * @package ShaplaTools
 * @version 1.0.0
 * @author Sayful Islam
 * @link http://sayful.net
 */

class ShaplaTools {

	/**
	* @var string
	*/
	public $version = '1.2.0';

	/**
	 * @var ShaplaTools The single instance of the class
	 * @since 1.2.0
	 */
	protected static $_instance = null;

	/**
	* @var string
	*/
	public $plugin_url;

	/**
	* @var string
	*/
	public $plugin_path;

	/**
	* @var string
	*/
	public $template_url;

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
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * ShaplaTools Constructor.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Define version constant
		define( 'SHAPLATOOLS_VERSION', $this->version );

		if ( ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ) {
			define( 'SCRIPT_SUFFIX', '' );
		} else {
			define( 'SCRIPT_SUFFIX', '.min' );
		}

		// Hooks
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'admin_menu', array( &$this, 'shapla_add_options_page' ) );
		add_action( 'wp_footer', array( &$this, 'inline_scripts' ) );

		add_action( 'after_setup_theme', array( &$this, 'editor_styles' ) );

		// Include required files
		$this->includes();
		$this->shapla_load_post_types();
	}

	public function editor_styles() {
		$shortcode_styles = $this->plugin_url() . '/assets/css/shapla-shortcodes.css';
		add_editor_style( $shortcode_styles );
	}

	/**
	 * Add custom links on plugins page.
	 *
	 * @access public
	 * @param mixed $links
	 * @return void
	 */
	public function action_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'options-general.php?page=shaplatools' ) . '">' . __( 'Settings', 'shapla' ) . '</a>'
		);

		return array_merge( $plugin_links, $links );
	}

	/**
	 * Initiate all the stuff.
	 *
	 * @return void
	 */
	function init() {
		$this->shapla_load_textdomain();

		add_action( 'wp_enqueue_scripts', array( &$this, 'frontend_style' ), 0 );
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_style' ), 0 );
		add_filter( 'body_class', array( &$this, 'body_class' ) );

		add_filter( 'contextual_help', array( &$this, 'contextual_help' ), 10, 3 );
	}

	/**
	 * Add ShaplaTools admin options.
	 *
	 * @global string $shaplatools_options One true options page
	 * @return void
	 */
	function shapla_add_options_page() {
		add_options_page( __( 'ShaplaTools Options', 'shapla' ), __( 'ShaplaTools', 'shapla' ), 'manage_options', 'shaplatools', 'shaplatools_options_page' );
	}

	/**
	 * Setup post types.
	 *
	 * @return void
	 */
	function shapla_load_post_types() {
		$this->options = get_option('shaplatools_options');

		if( isset($this->options['team']) && $this->options['team'] == 'on' ) run_shaplatools_team();
		if( isset($this->options['slide']) && $this->options['slide'] == 'on' ) run_shaplatools_slide();
		if( isset($this->options['feature']) && $this->options['feature'] == 'on' ) run_shaplatools_feature();
		if( isset($this->options['portfolio']) && $this->options['portfolio'] == 'on' ) run_shaplatools_portfolio();
		if( isset($this->options['testimonial']) && $this->options['testimonial'] == 'on' ) run_shaplatools_testimonial();

		if( isset($this->options['slide_meta']) && $this->options['slide_meta'] == 'on' ) run_shaplatools_nivoslide_meta();
		if( isset($this->options['team_meta']) && $this->options['team_meta'] == 'on' ) run_shaplatools_team_meta();
		if( isset($this->options['feature_meta']) && $this->options['feature_meta'] == 'on' ) run_shaplatools_feature_meta();
		if( isset($this->options['portfolio_meta']) && $this->options['portfolio_meta'] == 'on' ) run_shaplatools_portfolio_meta();
		if( isset($this->options['testimonial_meta']) && $this->options['testimonial_meta'] == 'on' ) run_shaplatools_testimonial_meta();
	}

	/**
	 * Setup localisation.
	 *
	 * @return void
	 */
	function shapla_load_textdomain() {
		// Set filter for plugin's languages directory
		$shaplatools_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
		$shaplatools_lang_dir = apply_filters( 'shaplatools_languages_directory', $shaplatools_lang_dir );

		// Traditional WordPress plugin locale filter
		$locale        = apply_filters( 'plugin_locale',  get_locale(), 'shapla' );
		$mofile        = sprintf( '%1$s-%2$s.mo', 'shapla', $locale );

		// Setup paths to current locale file
		$mofile_local  = $shaplatools_lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/shaplatools/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/shaplatools folder
			load_textdomain( 'shapla', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/shaplatools/languages/ folder
			load_textdomain( 'shapla', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'shapla', false, $shaplatools_lang_dir );
		}
	}

	/**
	 * Include admin and frontend files.
	 *
	 * @uses ShaplaTools::admin_includes() Includes admin files
	 * @uses ShaplaTools::frontend_includes() Includes frontend files
	 * @return void
	 */
	public function includes() {
		global $shaplatools_options;
		
		require_once('includes/settings/settings.php');
		$shaplatools_options = shaplatools_get_settings();
		
		include_once( 'includes/classes/Shapla_Retina_2x.php' );
		require_once('includes/classes/ShaplaTools_Typeahead.php');

		if ( is_admin() ){
			$this->admin_includes();
		}
		if( !is_admin() ){
			$this->frontend_includes();
		}

		// Widgets
		include_once( 'includes/widgets/widget-dribbble.php' );
		include_once( 'includes/widgets/widget-flickr.php' );
		include_once( 'includes/widgets/widget-instagram.php' );
		include_once( 'includes/widgets/widget-twitter.php' );
		include_once( 'includes/widgets/widget-fb_like_box.php' );
		include_once( 'includes/widgets/widget-contact.php' );
		include_once( 'includes/widgets/widget-testimonials.php' );

		// Post Types
		include_once( 'includes/post-type/ShaplaTools_Team.php' );
		include_once( 'includes/post-type/ShaplaTools_Slide.php' );
		include_once( 'includes/post-type/ShaplaTools_Feature.php' );
		include_once( 'includes/post-type/ShaplaTools_Portfolio.php' );
		include_once( 'includes/post-type/ShaplaTools_Testimonial.php' );

		// Meta Box
		include_once( 'includes/meta-box/ShaplaTools_NivoSlide_Metabox.php' );
		include_once( 'includes/meta-box/ShaplaTools_Portfolio_Metabox.php' );
		include_once( 'includes/meta-box/ShaplaTools_Feature_Metabox.php' );
		include_once( 'includes/meta-box/ShaplaTools_Testimonial_Metabox.php' );
		include_once( 'includes/meta-box/ShaplaTools_Team_Metabox.php' );
	}

	/**
	* Include admin files.
	*
	* @return void
	*/
	public function admin_includes(){
		include_once( 'shortcodes/shapla-shortcodes.php' );
		include_once( 'includes/settings/settings.php' );
		include_once( 'includes/classes/ShaplaTools_Gallery.php' );
		include_once( 'includes/classes/ShaplaTools_Metaboxs.php' );
		include_once( 'includes/classes/ShaplaTools_TinyMCE.php' );
	}

	/**
	 * Include frontend files.
	 *
	 * @return void
	 */
	public function frontend_includes(){
		include_once( plugin_dir_path( __FILE__ ) .'shortcodes/shortcodes.php' );
		include_once( plugin_dir_path( __FILE__ ) .'shortcodes/shortcodes-post_types.php' );
	}

	/**
	 * Add frontend scripts and styles.
	 *
	 * @return void
	 */
	public function frontend_style() {
		wp_register_style( 'font-awesome', $this->plugin_url() . '/assets/css/font-awesome'. SCRIPT_SUFFIX .'.css' , '', '4.3.0', 'all' );
		wp_register_style( 'shapla-shortcode-styles', $this->plugin_url() . '/assets/css/shapla-shortcodes.css' , array( 'font-awesome' ), $this->version, 'all' );

		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'shapla-shortcode-styles' );

		wp_register_script( 'shapla-shortcode-scripts', $this->plugin_url(). '/assets/js/shapla-shortcode-scripts.js', array( 'jquery', 'jquery-ui-accordion', 'jquery-ui-tabs' ), $this->version, true );

		/**!
		 * Enqueue ShaplaTools custom style
		 * Enqueue ShaplaTools custom script
		 */
		wp_enqueue_style( 'shaplatools', $this->plugin_url() . '/assets/css/shaplatools.css', array(), $this->version, 'all' );
		wp_enqueue_script( 'shaplatools', $this->plugin_url(). '/assets/js/shaplatools.js', array( 'jquery' ), $this->version, true );

		/**!
		 * Enqueue Owl Carousel plugin
		 * Enqueue Owl Carousel Style
		 */
		wp_register_style( 'owl-carousel', $this->plugin_url(). '/assets/library/owl-carousel/owl.carousel.css', array(), $this->version, 'all' );
		wp_register_style( 'owl-carousel-theme', $this->plugin_url(). '/assets/library/owl-carousel/owl.theme.green.css', array(), $this->version, 'all' );
		wp_register_script( 'owl-carousel', $this->plugin_url(). '/assets/library/owl-carousel/owl.carousel'. SCRIPT_SUFFIX .'.js', array( 'jquery' ), '2.0.0', true );
	}

	/**
	 * Add frontend scripts and styles.
	 *
	 * @return void
	 */
	public function admin_style( $hook ) {

		if( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'widgets.php' ) {

			wp_enqueue_style( 'shapla-admin', $this->plugin_url().'/assets/css/shaplatools-admin.css', array(), $this->version, 'all' );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'shapla-admin-script', $this->plugin_url(). '/assets/js/shapla-admin.js', array(  'jquery', 'jquery-ui-datepicker', 'wp-color-picker' ), $this->version, true );
		}

	}

	/**
	 * Plugin path.
	 *
	 * @return string Plugin path
	 */
	public function plugin_path() {
		if ( $this->plugin_path ) return $this->plugin_path;

		return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Plugin url.
	 *
	 * @return string Plugin url
	 */
	public function plugin_url() {
		if ( $this->plugin_url ) return $this->plugin_url;
		return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Add shaplatools to body class for use on frontend.
	 *
	 * @since 1.0.0
	 * @return array $classes List of classes
	 */
	public function body_class( $classes ) {
		$classes[] = 'shaplatools';
		return $classes;
	}

	/**
	 * Add help screen for ShaplaTools settings page.
	 *
	 * @param  string $contextual_help
	 * @param  string $screen_id       String of the settings page
	 * @param  object $screen          Current screen object containing all details
	 * @since  1.1
	 * @return object Help object
	 */
	function contextual_help( $contextual_help, $screen_id, $screen ) {
		if ( "settings_page_shaplatools" != $screen_id )
			return;

		$screen->set_help_sidebar(
			'<p><strong>' . sprintf( __( 'For more information:', 'shapla' ) . '</strong></p>' .
			'<p>' . sprintf( __( 'Visit the <a href="%s" target="_blank">documentation</a> on the WordPress Directory.', 'shapla' ), esc_url( 'https://wordpress.org/plugins/shaplatools/' ) ) ) . '</p>' .
			'<p>' . sprintf(
						__( '<a href="%s" target="_blank">Post an issue</a> on <a href="%s" target="_blank">Support Forum</a>.', 'shapla' ),
						esc_url( 'https://wordpress.org/support/plugin/shaplatools' ),
						esc_url( 'https://wordpress.org/support/plugin/shaplatools' )
					) . '</p>'
		);

		$screen->add_help_tab( array(
			'id'	    => 'shaplatools-help-general',
			'title'	    => __( 'ShaplaTools General Settings', 'shapla' ),
			'content'	=>  '<p>' . __( 'Here you can find how to configure general options of this plugin.', 'shapla' ) . '</p>'.
							'<h5>' . __( 'Google Analytics ID', 'shapla' ) . '</h5>'.
							'<p>' . sprintf( __( 'In order to use Google Analytics service, go to <a href="%s" target="_blank">Google Analytics</a> and click Access Google Analytics and register for a service for your site. You will get a Google Analytics ID like this formate (UA-XXXXX-X), paste this ID in Google Analytics ID field and click save.', 'shapla' ), esc_url( 'https://www.google.com/analytics/' ) ) . '</p>'.
							'<h5>'.__('Autocomplete search form').'</h5>'.
							'<p>'.sprintf( __('Autocomplete search form use <a href="%s" target="_blank">twitter typeahead.js JavaScript library</a> for AJAX search. In order to use this feature select "Enable for WordPress Default Search" to enable AJAX search for WordPress default search or select "Enable for WooCommerce Product Search" to enable WooCommerce products search.'), esc_url( 'https://twitter.github.io/typeahead.js/' ) ).'</p>'.
							'<h5>'.__('Retina graphics for your website').'</h5>'.
							'<p>'.sprintf( __('To serve high-resolution images to devices with retina displays. This plugin will use open source script <a href="%s" target="_blank">retina.js JavaScript library</a> and for using retina.js script, a higher quality version of image will be created and stored with @2x added to the filename when an image is uploaded.'), esc_url( 'http://imulus.github.io/retinajs/' ) ).'</p>'
		) );

		$screen->add_help_tab( array(
			'id'	    => 'shaplatools-help-social',
			'title'	    => __( 'Using Social Icons', 'shapla' ),
			'content'	=>  '<h5>'. __( 'Using Social Icons Shortcode' ) .'</h5>'.
							'<p>' . __( 'To use the social icon use the following shortcode:', 'shapla' ) . '</p>'.
							'<pre>[shapla_social] // '. __( 'It would display all social icons with non-empty profile URLs.', 'shapla' ) .'</pre>'.
							'<pre>[shapla_social id="facebook,twitter,google-plus"] // '. __( 'or you can pass specific ids.', 'shapla' ) .'</pre>'.
							'<h5>'. __( 'Using Different Styled Icons' ) .'</h5>'.
							'<p>'. __( 'You can use the social icons in two different styles: normal and square. Just pass the <code>style</code> argument in sidebar.<br>E.g.: <code>[shapla_social id="twitter,facebook" style="square"]</code>.' ) .'</p>'
		) );

		$screen->add_help_tab( array(
			'id'	    => 'shaplatools-help-custom_post',
			'title'	    => __( 'Custom Post Types', 'shapla' ),
			'content'	=>  '<h5>'. __( 'Using Custom Post Types' ) .'</h5>'.
							'<p>'.sprintf(  __( 'This plugin includes five most used custom post type. You can enable them by checking custom post name. Also check default meta for full functionality. If you want to add your own meta box in your theme function.php file, you can do that. For using custom post types and default meta in your theme, You can read documentation of plugin <a href="%s" target="_blank">FAQ page</a>.'), esc_url( 'https://wordpress.org/plugins/shaplatools/faq/' )) .'</p>'
		) );

		return $contextual_help;
	}

	public function inline_scripts(){
		$this->options = get_option('shaplatools_options');
		
		if( !empty($this->options['google_analytics']) ):
		?>
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','<?php echo esc_attr($this->options['google_analytics']); ?>','auto');ga('send','pageview');
        </script>
		<?php
		endif;
	}

}

/**
 * Returns the main instance of WC to prevent the need to use globals.
 *
 * @since  1.2.0
 * @return ShaplaTools
 */
function shaplatools() {
	return ShaplaTools::instance();
}

$GLOBALS['shaplatools'] = shaplatools();

}


/**
 * Flush the rewrite rules on activation
 * Flush the rewrite rules on deactivation
 */
function shaplatools_activation_deactivation() {

	ShaplaTools_Slide::post_type();
	ShaplaTools_Portfolio::post_type();
	ShaplaTools_Portfolio::taxonomy();
	ShaplaTools_Team::post_type();
	ShaplaTools_Feature::post_type();
	ShaplaTools_Testimonial::post_type();

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'shaplatools_activation_deactivation' );
register_deactivation_hook( __FILE__, 'shaplatools_activation_deactivation' );
