<?php

/**
 * Register a portfolio post type.
 * @package ShaplaTools
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
$portfolio_labels = apply_filters( 'shaplatools_portfolio_labels', array(
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

$supports = apply_filters( 'shaplatools_portfolio_supports', array( 'title', 'editor', 'thumbnail', 'revisions' ) );

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
	'supports'            => $supports
);

register_post_type( 'portfolio', apply_filters( 'shaplatools_portfolio_post_type_args', $args ) );

/**
 * Register a skill taxonomy for portfolio post type.
 * @package ShaplaTools
 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
 */
register_taxonomy( 'skill', 'portfolio', array(
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
