<?php

namespace ShaplaTools\Modules\Slider;

class MetaBox {

	private static $instance = null;
	private $slide_type = 'shaplatools_slide';

	/**
	 * @return MetaBox
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_filter( 'manage_edit-' . $this->slide_type . '_columns', array( $this, 'columns_head' ) );
		add_action( 'manage_' . $this->slide_type . '_posts_custom_column', array( $this, 'columns_content' ), 10, 2 );
	}

	private function available_img_size() {
		$shaplatools_img_size = get_intermediate_image_sizes();
		array_push( $shaplatools_img_size, 'full' );

		$singleArray = array();

		foreach ( $shaplatools_img_size as $key => $value ) {

			$singleArray[ $value ] = $value;
		}

		return $singleArray;
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box() {
		$meta_box = require SHAPLATOOLS_SLIDER_VIEWS . '/admin/nivo-slider.php';

		$nivoSlideMeta = new \ShaplaTools_Meta_Box();
		$nivoSlideMeta->add( $meta_box );
	}

	public function columns_head( $defaults ) {
		unset( $defaults['date'] );

		$defaults['id']        = __( 'Slider ID', 'shaplatools' );
		$defaults['shortcode'] = __( 'Shortcode', 'shaplatools' );

		return $defaults;
	}

	public function columns_content( $column_name, $post_id ) {

		if ( 'id' == $column_name ) {
			echo $post_id;
		}

		if ( 'shortcode' == $column_name ) {
			echo '<pre><code>[shapla_slide id="' . $post_id . '"]</pre></code>';
		}

	}
}

MetaBox::init();
