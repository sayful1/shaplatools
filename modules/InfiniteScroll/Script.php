<?php

namespace ShaplaTools\Modules\InfiniteScroll;

class Script {

	private static $instance = null;

	/**
	 * @return Script
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_style(
			'shaplatools-infinite-scroll-style',
			SHAPLATOOLS_INFINITE_SCROLL_ASSETS . '/css/infinite-scroll.css'
		);

		wp_enqueue_script(
			'shaplatools-infinite-scroll',
			SHAPLATOOLS_INFINITE_SCROLL_ASSETS . '/js/infinite-scroll.js',
			array( 'jquery' ),
			SHAPLATOOLS_VERSION,
			true
		);

		wp_localize_script( 'shaplatools-infinite-scroll', 'ShaplaToolsInfiniteScroll', $this->localize_script() );
	}

	private function localize_script() {
		$options = array(
			'navSelector'     => 'nav.pagination',
			'nextSelector'    => 'nav.pagination a.next',
			'itemSelector'    => 'article.post.type-post',
			'contentSelector' => '.content-area',
			'loader'          => SHAPLATOOLS_INFINITE_SCROLL_ASSETS . '/img/loader.gif',
			'shop'            => false,
		);

		if ( function_exists( 'WC' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
			$options['itemSelector'] = 'li.product.type-product';
			$options['shop']         = true;
		}

		return apply_filters( 'shaplatools_infinite_scroll_localize_script', $options );
	}
}

Script::init();
