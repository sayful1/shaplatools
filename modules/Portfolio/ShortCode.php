<?php

namespace ShaplaTools\Modules\Portfolio;

class ShortCode {

	private static $instance = null;

	/**
	 * @return ShortCode
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_shortcode( 'shapla_portfolio', array( $this, 'shapla_portfolio' ) );
	}

	/**
	 * A shortcode for rendering the shapla portfolio.
	 *
	 * @param  array $attributes Shortcode attributes.
	 * @param  string $content The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function shapla_portfolio( $attributes, $content = null ) {
		$default_attributes = array(
			'thumbnail'      => 'm4',
			'thumbnail_size' => 'medium'
		);

		$attributes = shortcode_atts( $default_attributes, $attributes );

		ob_start();
		require SHAPLATOOLS_PORTFOLIO_VIEWS . '/public/shapla_portfolio.php';
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}

ShortCode::init();
