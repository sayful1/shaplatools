<?php

if( !class_exists('ShaplaTools_Team') ):

class ShaplaTools_Team {

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
	 * Register a team post type.
	 * @package ShaplaTools
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public static function post_type(){

		$labels = array(
			'name'               => __( 'Team', 'shapla' ),
			'singular_name'      => __( 'Team', 'shapla' ),
			'add_new'            => __( 'Add New', 'shapla' ),
			'add_new_item'       => __( 'Add New Team Member', 'shapla' ),
			'edit_item'          => __( 'Edit Team Member', 'shapla' ),
			'new_item'           => __( 'New Team Member', 'shapla' ),
			'view_item'          => __( 'View Team Member', 'shapla' ),
			'search_items'       => __( 'Search Team Member', 'shapla' ),
			'not_found'          => __( 'No Team Member found', 'shapla' ),
			'not_found_in_trash' => __( 'No Team Member in trash', 'shapla' ),
			'parent_item_colon'  => ''
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => array('slug' => 'team'),
			'show_ui'             => true,
			'query_var'           => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_position'       => 34,
			'menu_icon'           => 'dashicons-groups',
			'has_archive'         => false,
			'supports'            => array( 'title', 'editor', 'thumbnail' )
		);

		register_post_type( 'team', $args );
	}
}

function run_shaplatools_team(){
	ShaplaTools_Team::get_instance();
}
//run_shaplatools_team();
endif;