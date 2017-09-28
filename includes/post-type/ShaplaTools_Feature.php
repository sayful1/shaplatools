<?php

if( !class_exists('ShaplaTools_Feature') ):

class ShaplaTools_Feature {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	public function __construct(){
		add_action( 'init', array ($this, 'post_type') );
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

	/**
	 * Register a feature post type.
	 * @package ShaplaTools
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public static function post_type(){

		$labels = array(
			'name'                => _x( 'Features', 'Post Type General Name', 'shapla' ),
			'singular_name'       => _x( 'Feature', 'Post Type Singular Name', 'shapla' ),
			'menu_name'           => __( 'Features', 'shapla' ),
			'parent_item_colon'   => __( 'Parent Feature:', 'shapla' ),
			'all_items'           => __( 'All Features', 'shapla' ),
			'view_item'           => __( 'View Feature', 'shapla' ),
			'add_new_item'        => __( 'Add New Feature', 'shapla' ),
			'add_new'             => __( 'Add New', 'shapla' ),
			'edit_item'           => __( 'Edit Feature', 'shapla' ),
			'update_item'         => __( 'Update Feature', 'shapla' ),
			'search_items'        => __( 'Search Feature', 'shapla' ),
			'not_found'           => __( 'Not found', 'shapla' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'shapla' ),
		);
		$args = array(
			'label'               => __( 'feature', 'shapla' ),
			'description'         => __( 'Add features', 'shapla' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 35,
			'menu_icon'           => 'dashicons-pressthis',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
		);
		register_post_type( 'feature', $args );
	}
}

function run_shaplatools_feature(){
	ShaplaTools_Feature::get_instance();
}
//run_shaplatools_feature();
endif;