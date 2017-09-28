<?php

if( !class_exists('ShaplaTools_Feature_Metabox') ):

class ShaplaTools_Feature_Metabox {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'admin_head', array ($this, 'add_mce_button') );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function add_mce_button() {
	    // check user permissions
	    if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
	        return;
	    }
	    // check if WYSIWYG is enabled
	    if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array ($this, 'add_tinymce_plugin') );
			add_filter( 'mce_buttons', array ($this, 'register_mce_button') );
	    }
	}

	public function add_tinymce_plugin( $plugin_array ) {
		global $shaplatools;
	    $plugin_array['shaplatools_feature_mce_button'] = $shaplatools->plugin_url() .'/assets/mce-button/mce-feature.js';
	    return $plugin_array;
	}

	public function register_mce_button( $buttons ) {
	    array_push( $buttons, 'shaplatools_feature_mce_button' );
	    return $buttons;
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box() {
		$meta_box = array(
		    'id' => 'shapla-metabox-feature',
		    'title' => __('Extra Settings', 'shapla'),
		    'page' => 'feature',
		    'context' => 'side',
		    'priority' => 'core',
		    'fields' => array(
		        array(
		            'name' => __('Font Awesome Icon Class:', 'shapla'),
		            'desc' => __('Add <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Font Awesome</a> Icon Class. Example: fa fa-wordpress', 'shapla'),
		            'id' => '_shapla_feature_fa_icon',
		            'type' => 'text',
		            'std' => ''
		        ),
		    )
		);
		$ShaplaTools_Metaboxs = new ShaplaTools_Metaboxs();
		$ShaplaTools_Metaboxs->shapla_add_meta_box($meta_box);
	}
}

function run_shaplatools_feature_meta(){
	if (is_admin())
		ShaplaTools_Feature_Metabox::get_instance();
}
//run_shaplatools_feature_meta();
endif;