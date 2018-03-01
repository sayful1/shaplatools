<?php

if ( ! class_exists( 'ShaplaTools_Grid_Shortcode' ) ) {

	class ShaplaTools_Grid_Shortcode {

		private static $instance;

		/**
		 * @return ShaplaTools_Grid_Shortcode
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * ShaplaTools_Grid_Shortcode constructor.
		 */
		public function __construct() {
			add_shortcode( 'shapla_row', array( __CLASS__, 'shapla_row' ) );
			add_shortcode( 'shapla_row_padding', array( __CLASS__, 'shapla_row_padding' ) );
			add_shortcode( 'shapla_col_one', array( __CLASS__, 'shapla_col_one' ) );
			add_shortcode( 'shapla_col_two', array( __CLASS__, 'shapla_col_two' ) );
			add_shortcode( 'shapla_col_three', array( __CLASS__, 'shapla_col_three' ) );
			add_shortcode( 'shapla_col_four', array( __CLASS__, 'shapla_col_four' ) );
			add_shortcode( 'shapla_col_five', array( __CLASS__, 'shapla_col_five' ) );
			add_shortcode( 'shapla_col_six', array( __CLASS__, 'shapla_col_six' ) );
			add_shortcode( 'shapla_col_seven', array( __CLASS__, 'shapla_col_seven' ) );
			add_shortcode( 'shapla_col_eight', array( __CLASS__, 'shapla_col_eight' ) );
			add_shortcode( 'shapla_col_nine', array( __CLASS__, 'shapla_col_nine' ) );
			add_shortcode( 'shapla_col_ten', array( __CLASS__, 'shapla_col_ten' ) );
			add_shortcode( 'shapla_col_eleven', array( __CLASS__, 'shapla_col_eleven' ) );
			add_shortcode( 'shapla_col_twelve', array( __CLASS__, 'shapla_col_twelve' ) );

			// Depreciated and merged with new shortcodes since 1.3.0
			add_shortcode( 'shapla_columns', array( __CLASS__, 'shapla_row_padding' ) );
			add_shortcode( 'shapla_one_third', array( __CLASS__, 'shapla_col_four' ) );
			add_shortcode( 'shapla_one_third_last', array( __CLASS__, 'shapla_col_four' ) );
			add_shortcode( 'shapla_two_third', array( __CLASS__, 'shapla_col_eight' ) );
			add_shortcode( 'shapla_two_third_last', array( __CLASS__, 'shapla_col_eight' ) );
			add_shortcode( 'shapla_one_half', array( __CLASS__, 'shapla_col_six' ) );
			add_shortcode( 'shapla_one_half_last', array( __CLASS__, 'shapla_col_six' ) );
			add_shortcode( 'shapla_one_fourth', array( __CLASS__, 'shapla_col_three' ) );
			add_shortcode( 'shapla_one_fourth_last', array( __CLASS__, 'shapla_col_three' ) );
			add_shortcode( 'shapla_three_fourth', array( __CLASS__, 'shapla_col_nine' ) );
			add_shortcode( 'shapla_three_fourth_last', array( __CLASS__, 'shapla_col_nine' ) );
			add_shortcode( 'shapla_one_sixth', array( __CLASS__, 'shapla_col_two' ) );
			add_shortcode( 'shapla_one_sixth_last', array( __CLASS__, 'shapla_col_two' ) );
			add_shortcode( 'shapla_five_sixth', array( __CLASS__, 'shapla_col_ten' ) );
			add_shortcode( 'shapla_five_sixth_last', array( __CLASS__, 'shapla_col_ten' ) );

			// Depreciated and backed up with new shortcodes since 1.3.0
			add_shortcode( 'shapla_one_fifth', array( __CLASS__, 'shapla_one_fifth' ) );
			add_shortcode( 'shapla_one_fifth_last', array( __CLASS__, 'shapla_one_fifth' ) );
			add_shortcode( 'shapla_two_fifth', array( __CLASS__, 'shapla_two_fifth' ) );
			add_shortcode( 'shapla_two_fifth_last', array( __CLASS__, 'shapla_two_fifth' ) );
			add_shortcode( 'shapla_three_fifth', array( __CLASS__, 'shapla_three_fifth' ) );
			add_shortcode( 'shapla_three_fifth_last', array( __CLASS__, 'shapla_three_fifth' ) );
			add_shortcode( 'shapla_four_fifth', array( __CLASS__, 'shapla_four_fifth' ) );
			add_shortcode( 'shapla_four_fifth_last', array( __CLASS__, 'shapla_four_fifth' ) );
		}

		/**
		 * A shortcode for rendering the shapla row with no padding.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_row( $atts, $content = null ) {
			return '<div class="shapla-row">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering the shapla row with padding.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_row_padding( $atts, $content = null ) {
			return '<div class="shapla-row-padding">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering one column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_one( $atts, $content = null ) {
			return '<div class="shapla-col m1 l1">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering two column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_two( $atts, $content = null ) {
			return '<div class="shapla-col m2 l2">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering three column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_three( $atts, $content = null ) {
			return '<div class="shapla-col m3 l3">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering four column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_four( $atts, $content = null ) {
			return '<div class="shapla-col m4 l4">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering five column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_five( $atts, $content = null ) {
			return '<div class="shapla-col m5 l5">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering six column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_six( $atts, $content = null ) {
			return '<div class="shapla-col m6 l6">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering seven column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_seven( $atts, $content = null ) {
			return '<div class="shapla-col m7 l7">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering eight column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_eight( $atts, $content = null ) {
			return '<div class="shapla-col m8 l8">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering nine column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_nine( $atts, $content = null ) {
			return '<div class="shapla-col m9 l9">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering ten column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_ten( $atts, $content = null ) {
			return '<div class="shapla-col m10 l10">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering eleven column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_eleven( $atts, $content = null ) {
			return '<div class="shapla-col m11 l11">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering twelve column on 12 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_col_twelve( $atts, $content = null ) {
			return '<div class="shapla-col m12 l12">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering two column on 10 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_one_fifth( $atts, $content = null ) {
			return '<div class="shapla-col m20 l20">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering four column on 10 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_two_fifth( $atts, $content = null ) {
			return '<div class="shapla-col m40 l40">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering six column on 10 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_three_fifth( $atts, $content = null ) {
			return '<div class="shapla-col m60 l60">' . do_shortcode( $content ) . '</div>';
		}

		/**
		 * A shortcode for rendering eight column on 10 grid system.
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
		public static function shapla_four_fifth( $atts, $content = null ) {
			return '<div class="shapla-col m80 l80">' . do_shortcode( $content ) . '</div>';
		}
	}
}

ShaplaTools_Grid_Shortcode::instance();
