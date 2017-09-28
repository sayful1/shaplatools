<?php

if( !class_exists('ShaplaTools_NivoSlide_Metabox') ):

class ShaplaTools_NivoSlide_Metabox {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'admin_head', array( $this, 'admin_style') );

		add_filter( 'manage_edit-slide_columns', array ($this, 'columns_head') );
		add_action( 'manage_slide_posts_custom_column', array ($this, 'columns_content') );
	}

	public function admin_style(){
		global $post_type;
		if( $post_type != 'slide' )
			return;

		$style  ='<style type="text/css">';
		$style .='#postimagediv {display: none;}';
		$style .='#slider-thumbs li {display: inline;margin-right: 6px;margin-bottom: 6px;}';
		$style .='</style>';

		echo $style;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function available_img_size(){
	    $shaplatools_img_size = get_intermediate_image_sizes();
	    array_push($shaplatools_img_size, 'full');

	    $singleArray = array();

	    foreach ($shaplatools_img_size as $key => $value){

	        $singleArray[$value] = $value;
	    }

	    return $singleArray;
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box() {
		$meta_box = array(
		    'id' => 'shapla-metabox-slide',
		    'title' => __('Slide Settings', 'shapla'),
		    'description' => __('To use this slider in your posts or pages use the following shortcode:<pre><code>[shapla_slide id="'.get_the_ID().'"]</code></pre><br>', 'shapla'),
		    'page' => 'slide',
		    'context' => 'normal',
		    'priority' => 'high',
		    'fields' => array(
		        array(
		            'name' => __('Slider Images', 'shapla'),
		            'desc' => __('Choose slider images.', 'shapla'),
		            'id' => '_shapla_slide_images',
		            'type' => 'images',
		            'std' => __('Upload Images', 'shapla')
		        ),
		        array(
		            'name' => __('Slider Image Size', 'shapla'),
		            'desc' => __('Select image size from available image size. Use full for original image size.', 'shapla'),
		            'id' => '_shapla_slide_img_size',
		            'type' => 'select',
		            'std' => 'full',
		            'options' => $this->available_img_size()
		        ),
		        array(
		            'name' => __('Slider Theme', 'shapla'),
		            'desc' => __('Use a pre-built theme. To use your own theme select "None".', 'shapla'),
		            'id' => '_shapla_slide_theme',
		            'type' => 'select',
		            'std' => 'sunny',
		            'options' => array(
		            	'none' 		=> __('None', 'shapla'),
		            	'default' 	=> __('Default', 'shapla'),
		            	'light' 	=> __('Light', 'shapla'),
		            	'dark' 		=> __('Dark', 'shapla'),
		            	'bar' 		=> __('Bar', 'shapla'),
		            	'smoothness'=> __('Smoothness', 'shapla'),
		            )
		        ),
		        array(
		            'name' => __('Transition Effect', 'shapla'),
		            'desc' => __('Select transition for for this slide.', 'shapla'),
		            'id' => '_shapla_slide_transition',
		            'type' => 'select',
		            'std' => 'random',
		            'options' => array(
		            	'random' 			=> __('random', 'shapla'),
		            	'sliceDown' 		=> __('sliceDown', 'shapla'),
		            	'sliceDownLeft' 	=> __('sliceDownLeft', 'shapla'),
		            	'sliceUp' 			=> __('sliceUp', 'shapla'),
		            	'sliceUpLeft' 		=> __('sliceUpLeft', 'shapla'),
		            	'sliceUpDown' 		=> __('sliceUpDown', 'shapla'),
		            	'sliceUpDownLeft' 	=> __('sliceUpDownLeft', 'shapla'),
		            	'fold' 				=> __('fold', 'shapla'),
		            	'fade' 				=> __('fade', 'shapla'),
		            	'slideInRight' 		=> __('slideInRight', 'shapla'),
		            	'slideInLeft' 		=> __('slideInLeft', 'shapla'),
		            	'boxRandom' 		=> __('boxRandom', 'shapla'),
		            	'boxRain' 			=> __('boxRain', 'shapla'),
		            	'boxRainReverse' 	=> __('boxRainReverse', 'shapla'),
		            	'boxRainGrow' 		=> __('boxRainGrow', 'shapla'),
		            	'boxRainGrowReverse'	=> __('boxRainGrowReverse', 'shapla')
		            )
		        ),
		        array(
		            'name' => __('Slices', 'shapla'),
		            'desc' => __('The number of slices to use in the "Slice" transitions (eg 15)', 'shapla'),
		            'id' => '_shapla_slide_slices',
		            'type' => 'text',
		            'std' => '15'
		        ),
		        array(
		            'name' => __('boxCols', 'shapla'),
		            'desc' => __('The number of columns to use in the "Box" transitions (eg 8)', 'shapla'),
		            'id' => '_shapla_slide_boxcols',
		            'type' => 'text',
		            'std' => '8'
		        ),
		        array(
		            'name' => __('boxRows', 'shapla'),
		            'desc' => __('The number of rows to use in the "Box" transitions (eg 4)', 'shapla'),
		            'id' => '_shapla_slide_boxrows',
		            'type' => 'text',
		            'std' => '4'
		        ),
		        array(
		            'name' => __('Animation Speed', 'shapla'),
		            'desc' => __('The speed of the transition animation in milliseconds (eg 500)', 'shapla'),
		            'id' => '_shapla_slide_animation_speed',
		            'type' => 'text',
		            'std' => '500'
		        ),
		        array(
		            'name' => __('Pause Time', 'shapla'),
		            'desc' => __('The amount of time to show each slide in milliseconds (eg 3000)', 'shapla'),
		            'id' => '_shapla_slide_pause_time',
		            'type' => 'text',
		            'std' => '3000'
		        ),
		        array(
		            'name' => __('Start Slide', 'shapla'),
		            'desc' => __('Set which slide the slider starts from (zero based index: usually 0)', 'shapla'),
		            'id' => '_shapla_slide_start',
		            'type' => 'text',
		            'std' => '0'
		        ),
		        array(
		            'name' => __('Enable Thumbnail Navigation', 'shapla'),
		            'desc' => '',
		            'id' => '_shapla_slide_thumb_nav',
		            'type' => 'checkbox',
		            'std' => ''
		        ),
		        array(
		            'name' => __('Enable Direction Navigation', 'shapla'),
		            'desc' => __('Prev & Next arrows', 'shapla'),
		            'id' => '_shapla_slide_dir_nav',
		            'type' => 'checkbox',
		            'std' => true
		        ),
		        array(
		            'name' => __('Enable Control Navigation', 'shapla'),
		            'desc' => __('eg 1,2,3...', 'shapla'),
		            'id' => '_shapla_slide_ctrl_nav',
		            'type' => 'checkbox',
		            'std' => true
		        ),
		        array(
		            'name' => __('Pause the Slider on Hover', 'shapla'),
		            'desc' => '',
		            'id' => '_shapla_slide_hover_pause',
		            'type' => 'checkbox',
		            'std' => true
		        ),
		        array(
		            'name' => __('Manual Transitions', 'shapla'),
		            'desc' => __('Slider is always paused', 'shapla'),
		            'id' => '_shapla_slide_transition_man',
		            'type' => 'checkbox',
		            'std' => ''
		        ),
		        array(
		            'name' => __('Random Start Slide', 'shapla'),
		            'desc' => __('Overrides Start Slide value', 'shapla'),
		            'id' => '_shapla_slide_start_rand',
		            'type' => 'checkbox',
		            'std' => ''
		        ),
		    )
		);
		$ShaplaTools_Metaboxs = new ShaplaTools_Metaboxs();
		$ShaplaTools_Metaboxs->shapla_add_meta_box($meta_box);
	}

	public function columns_head( $defaults ) {
		unset( $defaults['date'] );

		$defaults['id'] 		= __( 'Slide ID', 'shapla' );
		$defaults['shortcode'] 	= __( 'Shortcode', 'shapla' );
		$defaults['images'] 	= __( 'Images', 'shapla' );

		return $defaults;
	}

	public function columns_content( $column_name ) {

		$image_ids 	= explode(',', get_post_meta( get_the_ID(), '_shapla_image_ids', true) );

		if ( 'id' == $column_name ) {
			echo get_the_ID();
		}

		if ( 'shortcode' == $column_name ) {
			echo '<pre><code>[shapla_slide id="'.get_the_ID().'"]</pre></code>';
		}

		if ( 'images' == $column_name ) {
			?>
			<ul id="slider-thumbs" class="slider-thumbs">
				<?php

				foreach ( $image_ids as $image ) {
					if(!$image) continue;
					$src = wp_get_attachment_image_src( $image, array(50,50) );
					echo "<li><img src='{$src[0]}' width='{$src[1]}' height='{$src[2]}'></li>";
				}

				?>
			</ul>
			<?php
		}
	}
}

function run_shaplatools_nivoslide_meta(){
	if (is_admin())
		ShaplaTools_NivoSlide_Metabox::get_instance();
}
//run_shaplatools_nivoslide_meta();
endif;