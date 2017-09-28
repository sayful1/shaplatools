<?php

namespace ShaplaTools\Modules\Portfolio;

class Taxonomy {

	private static $instance = null;
	private $post_type = 'portfolio';

	/**
	 * @return Taxonomy
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'shaplatools_activation', array( $this, 'register_taxonomy' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
	}

	/**
	 * Register a skill taxonomy for portfolio post type.
	 * @package ShaplaTools
	 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	public function register_taxonomy() {
		register_taxonomy( 'skill', $this->post_type, array(
			'label'             => __( 'Skills', 'shaplatools' ),
			'singular_label'    => __( 'Skill', 'shaplatools' ),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'args'              => array( 'orderby' => 'term_order' ),
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'skill', 'hierarchical' => true )
		) );
	}
}

Taxonomy::init();
