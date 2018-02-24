<?php

class ShaplaTools_Typeahead_Search {

	public $options;

	public function __construct( $options ) {
		$this->options = $options;

		add_action( 'wp_ajax_nopriv_shapla_search', array( $this, 'shaplatools_search' ) );
		add_action( 'wp_ajax_shapla_search', array( $this, 'shaplatools_search' ) );
	}

	/**
	 * Ajax query for the search
	 *
	 * @since 1.0.0
	 */
	public function shaplatools_search() {

		if ( wp_verify_nonce( $_REQUEST['_wpnonce'], 'shaplatools_nonce' ) ) {

			$args = array(
				's'              => $_REQUEST['terms'],
				'posts_per_page' => 10,
				'no_found_rows'  => true,
			);

			if (
				isset( $this->options['typeahead_search'] ) &&
				$this->options['typeahead_search'] == 'product_search' ) {
				$args['post_type'] = 'product';
			}

			$search_query = new WP_Query( $args );

			$results = array();

			if ( $search_query->get_posts() ) {
				foreach ( $search_query->get_posts() as $the_post ) {
					$img_url = wp_get_attachment_thumb_url( get_post_thumbnail_id( $the_post ) );

					if ( ! $img_url ) {
						$img_url = SHAPLATOOLS_ASSETS . '/assets/img/no-image.svg';
					}

					$results[] = array(
						'value'   => get_the_title( $the_post->ID ),
						'url'     => get_permalink( $the_post->ID ),
						'img_url' => $img_url,
					);
				}
			}

			wp_reset_postdata();
			echo json_encode( $results );
		}
		wp_die();
	}
}
