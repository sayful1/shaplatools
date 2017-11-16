<?php

namespace ShaplaTools\Modules\Search;

class AjaxSearchResponse {

	private $options;
	private static $instance = null;

	/**
	 * @return AjaxSearchResponse
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {

		$this->options = get_option( 'shaplatools_options' );

		add_action( 'wp_ajax_nopriv_shaplatools_search', array( $this, 'shaplatools_search' ) );
		add_action( 'wp_ajax_shaplatools_search', array( $this, 'shaplatools_search' ) );
	}

	/**
	 * Ajax query for the search
	 *
	 * @since 1.0.0
	 */
	public function shaplatools_search() {

		if ( ! isset( $_GET['search_nonce'], $_GET['s'] ) ) {
			$this->send_json( __( 'You are not allowed to perform this action.', 'shaplatools' ), 422 );
		}

		if ( ! wp_verify_nonce( $_GET['search_nonce'], 'shaplatools_nonce' ) ) {
			$this->send_json( __( 'You are not allowed to perform this action.', 'shaplatools' ), 422 );
		}

		$time_start         = $this->getmicrotime();
		$search_query       = isset( $_GET['s'] ) ? trim( $_GET['s'] ) : '';
		$transient_enabled  = apply_filters( 'shaplatools_ajax_search_transient_enabled', 'no' );
		$transient_name     = 'shaplatools_search_' . md5( $search_query );
		$transient_duration = 12;
		$suggestions        = array();

		if ( $transient_enabled == 'no' || false === ( $suggestions = get_transient( $transient_name ) ) ) {

			$args = array(
				's'                   => apply_filters( 'shaplatools_ajax_search_query', $search_query ),
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => apply_filters( 'shaplatools_ajax_search_posts_per_page', 10 ),
				'suppress_filters'    => false
			);

			if ( function_exists( 'wc' ) ) {
				$ordering_args = wc()->query->get_catalog_ordering_args( 'title', 'desc' );

				$args['post_type'] = [ 'product', 'product_variation' ];
				$args['orderby']   = $ordering_args['orderby'];
				$args['order']     = $ordering_args['order'];

				// Product Categories
				if ( ! empty( $_REQUEST['product_cat'] ) ) {
					$args['tax_query'] = array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'slug',
							'terms'    => $_REQUEST['product_cat']
						)
					);
				}

				if ( version_compare( wc()->version, '2.7.0', '<' ) ) {
					$args['meta_query'] = array(
						array(
							'key'     => '_visibility',
							'value'   => array( 'search', 'visible' ),
							'compare' => 'IN'
						),
					);
				} else {
					$product_visibility_term_ids = wc_get_product_visibility_term_ids();
					$args['tax_query'][]         = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'term_taxonomy_id',
						'terms'    => $product_visibility_term_ids['exclude-from-search'],
						'operator' => 'NOT IN',
					);
				}
			}

			$_posts = get_posts( $args );

			if ( ! empty( $_posts ) ) {
				foreach ( $_posts as $_post ) {
					if ( function_exists( 'wc' ) ) {
						$product       = wc_get_product( $_post );
						$suggestions[] = apply_filters( 'shaplatools_product_suggestion', array(
							'id'    => $product->get_id(),
							'value' => strip_tags( $product->get_title() ),
							'url'   => $product->get_permalink()
						), $product );
					} else {
						$suggestions[] = array(
							'id'    => $_post->ID,
							'value' => get_the_title( $_post ),
							'url'   => get_permalink( $_post ),
						);
					}
				}
			} else {
				$suggestions[] = array(
					'id'    => - 1,
					'value' => __( 'No results', 'shaplatools' ),
					'url'   => '',
				);
			}
			wp_reset_postdata();

			set_transient( $transient_name, $suggestions, $transient_duration * HOUR_IN_SECONDS );
		}

		$time_end    = $this->getmicrotime();
		$time        = $time_end - $time_start;
		$suggestions = array(
			'suggestions' => $suggestions,
			'time'        => $time
		);

		$this->send_json( $suggestions );
	}

	/**
	 * Send a JSON response back to an Ajax request.
	 *
	 * @param mixed $response Variable (usually an array or object) to encode as JSON, then print and die.
	 * @param int $status_code The HTTP status code to output.
	 */
	private function send_json( $response, $status_code = 200 ) {
		@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );

		status_header( $status_code );

		echo wp_json_encode( $response );

		if ( wp_doing_ajax() ) {
			wp_die( '', '', array(
				'response' => null,
			) );
		} else {
			die;
		}
	}

	/**
	 * Get current Unix timestamp with microseconds
	 *
	 * @return float
	 */
	private function getmicrotime() {
		list( $microseconds, $seconds ) = explode( " ", microtime() );

		return ( (float) $microseconds + (float) $seconds );
	}
}

AjaxSearchResponse::init();
