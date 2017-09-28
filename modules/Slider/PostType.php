<?php

namespace ShaplaTools\Modules\Slider;

class PostType {

	private static $instance = null;
	private $slide_type = 'shaplatools_slide';

	/**
	 * @return PostType
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'shaplatools_activation', array( $this, 'post_type' ) );
		add_action( 'init', array( $this, 'post_type' ) );
	}

	/**
	 * Register a slide post type.
	 * @package ShaplaTools
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function post_type() {
		$labels = array(
			'name'               => _x( 'Sliders', 'Post Type General Name', 'shaplatools' ),
			'singular_name'      => _x( 'Slider', 'Post Type Singular Name', 'shaplatools' ),
			'menu_name'          => __( 'Shapla Slider', 'shaplatools' ),
			'name_admin_bar'     => __( 'Slider', 'shaplatools' ),
			'parent_item_colon'  => __( 'Parent Slider:', 'shaplatools' ),
			'all_items'          => __( 'All Sliders', 'shaplatools' ),
			'add_new_item'       => __( 'Add New Slider', 'shaplatools' ),
			'add_new'            => __( 'Add New', 'shaplatools' ),
			'new_item'           => __( 'New Slider', 'shaplatools' ),
			'edit_item'          => __( 'Edit Slider', 'shaplatools' ),
			'update_item'        => __( 'Update Slider', 'shaplatools' ),
			'view_item'          => __( 'View Slider', 'shaplatools' ),
			'search_items'       => __( 'Search Slider', 'shaplatools' ),
			'not_found'          => __( 'Not found', 'shaplatools' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'shaplatools' ),
		);
		$args   = array(
			'label'               => __( 'Slider', 'shaplatools' ),
			'description'         => __( 'Create slider for your site', 'shaplatools' ),
			'labels'              => apply_filters( 'shaplatools_slider_labels', $labels ),
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 35,
			'menu_icon'           => 'dashicons-slides',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => false,
			'capability_type'     => 'page',
		);

		register_post_type( $this->slide_type, apply_filters( 'shaplatools_slider_args', $args ) );
	}
}

PostType::init();
