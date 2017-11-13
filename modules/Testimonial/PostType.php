<?php

namespace ShaplaTools\Modules\Testimonial;


class PostType {

	private static $instance = null;
	private $slide_type = 'testimonial';

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
	 * Register a testimonial post type.
	 * @package ShaplaTools
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function post_type() {

		$labels = array(
			'name'               => _x( 'Testimonials', 'Post Type General Name', 'shaplatools' ),
			'singular_name'      => _x( 'Testimonial', 'Post Type Singular Name', 'shaplatools' ),
			'menu_name'          => __( 'Testimonials', 'shaplatools' ),
			'parent_item_colon'  => __( 'Parent Testimonial:', 'shaplatools' ),
			'all_items'          => __( 'All Testimonials', 'shaplatools' ),
			'view_item'          => __( 'View Testimonial', 'shaplatools' ),
			'add_new_item'       => __( 'Add New Testimonial', 'shaplatools' ),
			'add_new'            => __( 'Add New', 'shaplatools' ),
			'edit_item'          => __( 'Edit Testimonial', 'shaplatools' ),
			'update_item'        => __( 'Update Testimonial', 'shaplatools' ),
			'search_items'       => __( 'Search Testimonial', 'shaplatools' ),
			'not_found'          => __( 'Not found', 'shaplatools' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'shaplatools' ),
		);
		$args   = array(
			'label'               => __( 'Testimonial', 'shaplatools' ),
			'description'         => __( 'Create testimonial for your site', 'shaplatools' ),
			'labels'              => apply_filters( 'shaplatools_testimonial_labels', $labels ),
			'supports'            => array( 'title', 'editor', 'thumbnail', ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 30,
			'menu_icon'           => 'dashicons-testimonial',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => array( 'slug' => 'testimonials', ),
			'capability_type'     => 'page',
		);

		register_post_type( $this->slide_type, apply_filters( 'shaplatools_testimonial_args', $args ) );
	}
}

PostType::init();
