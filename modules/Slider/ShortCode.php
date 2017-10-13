<?php

namespace ShaplaTools\Modules\Slider;

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
		add_shortcode( 'shapla_slider', array( $this, 'shapla_slider' ) );
	}

	/**
	 * A shortcode for rendering the shapla slide.
	 *
	 * @param  array $attributes Shortcode attributes.
	 * @param  string $content The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function shapla_slider( $attributes, $content = null ) {
		$default_attributes = array(
			'id' => 0
		);

		$attributes = shortcode_atts( $default_attributes, $attributes );

		ob_start();
		require SHAPLATOOLS_SLIDER_PATH . '/views/public/shapla_slide.php';
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}

ShortCode::init();
