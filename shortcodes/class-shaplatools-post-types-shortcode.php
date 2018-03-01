<?php
if ( ! class_exists( 'ShaplaTools_Post_Types_Shortcode' ) ) {

	class ShaplaTools_Post_Types_Shortcode {

		private static $instance;

		/**
		 * @return ShaplaTools_Post_Types_Shortcode
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			add_shortcode( 'shapla_slide', array( __CLASS__, 'shapla_slide' ) );
			add_shortcode( 'shapla_portfolio', array( __CLASS__, 'shapla_portfolio' ) );
		}

		/**
		 * A shortcode for rendering the shapla slide.
		 *
		 * @param  array $attributes Shortcode attributes.
		 * @param  string $content The text content for shortcode. Not used.
		 *
		 * @return string  The shortcode output
		 */
		public static function shapla_slide( $attributes, $content = null ) {
			$default_attributes = array(
				'id' => ''
			);

			$attributes = shortcode_atts( $default_attributes, $attributes );

			ob_start();
			require SHAPLATOOLS_TEMPLATES . '/shapla_slide.php';
			$html = ob_get_contents();
			ob_end_clean();

			return $html;
		}

		/**
		 * A shortcode for rendering the shapla portfolio.
		 *
		 * @param  array $attributes Shortcode attributes.
		 * @param  string $content The text content for shortcode. Not used.
		 *
		 * @return string  The shortcode output
		 */
		public static function shapla_portfolio( $attributes, $content = null ) {
			$default_attributes = array(
				'thumbnail'      => 'm4',
				'thumbnail_size' => 'medium'
			);

			$attributes = shortcode_atts( $default_attributes, $attributes );

			ob_start();
			require_once SHAPLATOOLS_TEMPLATES . '/shapla_portfolio.php';
			$html = ob_get_contents();
			ob_end_clean();

			return $html;
		}
	}
}

ShaplaTools_Post_Types_Shortcode::instance();
