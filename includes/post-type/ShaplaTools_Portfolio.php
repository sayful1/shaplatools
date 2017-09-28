<?php

if( !class_exists('ShaplaTools_Portfolio') ):

class ShaplaTools_Portfolio {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;


	public function __construct(){
		add_action( 'init', array ($this, 'post_type') );
		add_action( 'init', array ($this, 'taxonomy') );
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
	 * Register a portfolio post type.
	 * @package ShaplaTools
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public static function post_type(){

		$portfolio_labels = apply_filters( 'shapla_portfolio_labels', array(
			'name'               => __( 'Portfolio', 'shapla' ),
			'singular_name'      => __( 'Portfolio', 'shapla' ),
			'add_new'            => __( 'Add New', 'shapla' ),
			'add_new_item'       => __( 'Add New Portfolio', 'shapla' ),
			'edit_item'          => __( 'Edit Portfolio', 'shapla' ),
			'new_item'           => __( 'New Portfolio', 'shapla' ),
			'view_item'          => __( 'View Portfolio', 'shapla' ),
			'search_items'       => __( 'Search Portfolio', 'shapla' ),
			'not_found'          => __( 'No Portfolios found', 'shapla' ),
			'not_found_in_trash' => __( 'No Portfolios found in trash', 'shapla' ),
			'parent_item_colon'  => ''
		) );

		$args = array(
			'labels'              => $portfolio_labels,
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => array('slug' => 'portfolio', 'with_front' => false,),
			'show_ui'             => true,
			'query_var'           => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_position'       => 33,
			'menu_icon'           => 'dashicons-portfolio',
			'has_archive'         => false,
			'supports'            => array( 'title','editor', 'thumbnail', 'revisions' )
		);

		register_post_type( 'portfolio', $args );
	}

	/**
	 * Register a skill taxonomy for portfolio post type.
	 * @package ShaplaTools
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	public static function taxonomy(){
		register_taxonomy( 'skill', 'portfolio', array(
			'label'             => __( 'Skills', 'shapla' ),
			'singular_label'    => __( 'Skill', 'shapla' ),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'args'              => array( 'orderby' => 'term_order' ),
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'skill', 'hierarchical' => true)
		) );
	}
}

function run_shaplatools_portfolio(){
	ShaplaTools_Portfolio::get_instance();
}
//run_shaplatools_portfolio();
endif;