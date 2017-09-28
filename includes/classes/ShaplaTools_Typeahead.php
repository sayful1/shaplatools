<?php

class ShaplaTools_Typeahead {
	public $plugin_url;

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( &$this, 'typeahead_scripts' ) );
		add_action( 'wp_ajax_nopriv_ajax_search', array( $this, 'shapla_woo_ajax_search' ) );
		add_action( 'wp_ajax_ajax_search', array( $this, 'shapla_woo_ajax_search' ) );
	}

	public function typeahead_scripts(){

		global $shaplatools;
		$this->options = get_option('shaplatools_options');

		wp_register_style( 'shapla-typeahead-search', $shaplatools->plugin_url(). '/assets/library/typeahead/typeahead.css' , array(), SHAPLATOOLS_VERSION, 'all' );

		wp_register_script( 'typeahead_js', $shaplatools->plugin_url(). '/assets/library/typeahead/typeahead'. SCRIPT_SUFFIX .'.js', array('jquery'), '0.9.0', true );
		wp_register_script( 'hogan_js', $shaplatools->plugin_url(). '/assets/library/typeahead/hogan'. SCRIPT_SUFFIX .'.js', array('typeahead_js'), SHAPLATOOLS_VERSION, true );


	    if ( isset($this->options['typeahead_search']) && $this->options['typeahead_search'] != 'no_search' ) {
			wp_enqueue_style( 'shapla-typeahead-search' );
			wp_enqueue_script( 'typeahead_js' );
			wp_enqueue_script( 'hogan_js' );

			if (isset($this->options['typeahead_search']) && $this->options['typeahead_search'] == 'product_search') {
				wp_enqueue_script( 'typeahead_woo_search', $shaplatools->plugin_url(). '/assets/library/typeahead/woo-typeahead.js', array('typeahead_js', 'hogan_js'), '', true );
				wp_localize_script( 'typeahead_woo_search', 'wp_typeahead', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			} else {
				wp_enqueue_script( 'typeahead_general_search', $shaplatools->plugin_url(). '/assets/library/typeahead/wp-typeahead.js', array('typeahead_js', 'hogan_js'), '', true );
				wp_localize_script( 'typeahead_general_search', 'wp_typeahead', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			}
	    }
	}

	/**
	 * Ajax query for the search
	 *
	 * @since 1.0.0
	 */
	public function shapla_woo_ajax_search() {

		$this->options = get_option('shaplatools_options');

		if ( isset( $_REQUEST['fn'] ) && 'get_ajax_search' == $_REQUEST['fn'] ) {

			$args = array(
				's' => $_REQUEST['terms'],
				'posts_per_page' => 10,
				'no_found_rows' => true,
			);
			if (isset($this->options['typeahead_search']) && $this->options['typeahead_search'] == 'product_search' ) {
				$args['post_type'] = 'product';
			}

			$search_query = new WP_Query( $args );

			$results = array( );

			if ( $search_query->get_posts() ) {
				foreach ( $search_query->get_posts() as $the_post ) {
					$title = get_the_title( $the_post->ID );
					$img_url = wp_get_attachment_thumb_url( get_post_thumbnail_id($the_post->ID), 'thumbnail' );
					$results[] = array(
						'value' => $title,
						'img_url' => $img_url,
						'url' => get_permalink( $the_post->ID ),
						'tokens' => explode( ' ', $title ),
					);
				}
			} else {
				$results[] = __( 'Sorry. No results match your search.', 'shapla' );
			}

			wp_reset_postdata();
			echo json_encode( $results );
		}
		die();
	}
}
$shaplatools_typeahead = new ShaplaTools_Typeahead;
