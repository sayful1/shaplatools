<?php

namespace ShaplaTools\Modules\Testimonial;


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
		add_shortcode( 'shapla_testimonial', array( $this, 'shapla_testimonial' ) );
	}

	/**
	 * A shortcode for rendering the shapla testimonial.
	 *
	 * @param  array $attributes Shortcode attributes.
	 * @param  string $content The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function shapla_testimonial( $attributes, $content = null ) {
		$default_attributes = array(
			'id'                 => uniqid(),
			'posts_per_page'     => - 1,
			'orderby'            => 'none',
			'items_desktop'      => 4,
			'items_tablet'       => 3,
			'items_tablet_small' => 2,
			'items_mobile'       => 1,
		);

		$atts = shortcode_atts( $default_attributes, $attributes );

		ob_start();
		require_once SHAPLATOOLS_TESTIMONIAL_VIEWS . '/public/shapla_testimonial.php';
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}

ShortCode::init();
