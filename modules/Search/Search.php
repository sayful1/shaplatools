<?php

namespace ShaplaTools\Modules\Search;

class Search {

	private $options;
	private static $instance = null;

	/**
	 * @return Search
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {

		$this->options = get_option( 'shaplatools_options' );

		add_action( 'wp_ajax_nopriv_shapla_search', array( $this, 'shaplatools_search' ) );
		add_action( 'wp_ajax_shapla_search', array( $this, 'shaplatools_search' ) );
	}

	/**
	 * Ajax query for the search
	 *
	 * @since 1.0.0
	 */
	public function shaplatools_search() {

		if ( ! isset( $_GET['_wpnonce'], $_GET['terms'] ) ) {
			status_header( 422 );
			wp_send_json( __( 'You are not allowed to perform this action.', 'shaplatools' ) );
		}

		if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'shaplatools_nonce' ) ) {
			status_header( 422 );
			wp_send_json( __( 'You are not allowed to perform this action.', 'shaplatools' ) );
		}

		$_s = isset( $_GET['terms'] ) ? trim( $_GET['terms'] ) : '';

		$args = array(
			's'              => $_s,
			'posts_per_page' => 10,
			'no_found_rows'  => true,
		);

		if (
			isset( $this->options['typeahead_search'] ) &&
			$this->options['typeahead_search'] == 'product_search' ) {
			$args['post_type'] = 'product';
		}

		$search_query = new \WP_Query( $args );

		$results = array();

		if ( $search_query->get_posts() ) {
			foreach ( $search_query->get_posts() as $the_post ) {
				$img_url = wp_get_attachment_thumb_url( get_post_thumbnail_id( $the_post ) );

				if ( ! $img_url ) {
					$img_url = SHAPLATOOLS_ASSETS . '/img/no-image.svg';
				}

				$results[] = array(
					'value'   => get_the_title( $the_post ),
					'url'     => get_permalink( $the_post ),
					'img_url' => $img_url,
				);
			}
		}

		status_header( 200 );
		wp_send_json( $results );
	}
}

Search::init();
