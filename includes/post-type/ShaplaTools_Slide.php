<?php

if( !class_exists('ShaplaTools_Slide') ):

class ShaplaTools_Slide {

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
	 * Register a slide post type.
	 * @package ShaplaTools
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public static function post_type() {

		$labels = array(
			'name'                => _x( 'Slides', 'Post Type General Name', 'shapla' ),
			'singular_name'       => _x( 'Slide', 'Post Type Singular Name', 'shapla' ),
			'menu_name'           => __( 'Slide', 'shapla' ),
			'name_admin_bar'      => __( 'Slide', 'shapla' ),
			'parent_item_colon'   => __( 'Parent Slide:', 'shapla' ),
			'all_items'           => __( 'All Slides', 'shapla' ),
			'add_new_item'        => __( 'Add New Slide', 'shapla' ),
			'add_new'             => __( 'Add New', 'shapla' ),
			'new_item'            => __( 'New Slide', 'shapla' ),
			'edit_item'           => __( 'Edit Slide', 'shapla' ),
			'update_item'         => __( 'Update Slide', 'shapla' ),
			'view_item'           => __( 'View Slide', 'shapla' ),
			'search_items'        => __( 'Search Slide', 'shapla' ),
			'not_found'           => __( 'Not found', 'shapla' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'shapla' ),
		);
		$args = array(
			'label'               => __( 'slide', 'shapla' ),
			'description'         => __( 'Create slide for your site', 'shapla' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail', ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 35,
			'menu_icon'           => 'dashicons-images-alt2',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => false,
			'capability_type'     => 'post',
		);
		register_post_type( 'slide', $args );

	}
}

function run_shaplatools_slide(){
	ShaplaTools_Slide::get_instance();
}
//run_shaplatools_slide();
endif;