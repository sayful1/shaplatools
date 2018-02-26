<?php
if ( ! class_exists( 'ShaplaTools_Post_Types_Shortcode' ) ) {

	class ShaplaTools_Post_Types_Shortcode {

		public function __construct() {
			add_shortcode( 'shapla_slide', array( $this, 'shapla_slide' ) );
			add_shortcode( 'shapla_portfolio', array( $this, 'shapla_portfolio' ) );
		}

		/**
		 * A shortcode for rendering the shapla slide.
		 *
		 * @param  array $attributes Shortcode attributes.
		 * @param  string $content The text content for shortcode. Not used.
		 *
		 * @return string  The shortcode output
		 */
		public function shapla_slide( $attributes, $content = null ) {
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
		public function shapla_portfolio( $attributes, $content = null ) {
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
