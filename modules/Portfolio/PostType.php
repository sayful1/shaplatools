<?php

namespace ShaplaTools\Modules\Portfolio;


class PostType {

	private static $instance = null;
	private $post_type = 'portfolio';

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
	 * Register a portfolio post type.
	 * @package ShaplaTools
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function post_type() {

		$portfolio_labels = apply_filters( 'shapla_portfolio_labels', array(
			'name'               => __( 'Portfolio', 'shaplatools' ),
			'singular_name'      => __( 'Portfolio', 'shaplatools' ),
			'add_new'            => __( 'Add New', 'shaplatools' ),
			'add_new_item'       => __( 'Add New Portfolio', 'shaplatools' ),
			'edit_item'          => __( 'Edit Portfolio', 'shaplatools' ),
			'new_item'           => __( 'New Portfolio', 'shaplatools' ),
			'view_item'          => __( 'View Portfolio', 'shaplatools' ),
			'search_items'       => __( 'Search Portfolio', 'shaplatools' ),
			'not_found'          => __( 'No Portfolios found', 'shaplatools' ),
			'not_found_in_trash' => __( 'No Portfolios found in trash', 'shaplatools' ),
			'parent_item_colon'  => ''
		) );

		$args = array(
			'labels'              => $portfolio_labels,
			'public'              => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => array( 'slug' => 'portfolio', 'with_front' => false, ),
			'show_ui'             => true,
			'query_var'           => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_position'       => 33,
			'menu_icon'           => 'dashicons-portfolio',
			'has_archive'         => false,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' )
		);

		register_post_type( $this->post_type, apply_filters( 'shapla_portfolio_args', $args ) );
	}
}

PostType::init();
