<?php

if( !class_exists('ShaplaTools_Testimonial_Metabox') ):

class ShaplaTools_Testimonial_Metabox {

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
		if (is_admin()){
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'add_meta_boxes', array( $this, 'client_avatar' ) );
			
			add_action( 'admin_head', array ($this, 'add_mce_button') );

			add_filter( 'manage_edit-testimonial_columns', array ($this, 'columns_head') );
			add_action( 'manage_testimonial_posts_custom_column', array ($this, 'columns_content'));
		} else {
			add_action( 'wp_enqueue_scripts', array( &$this, 'frontend_scripts' ) );
		}
	}

	public function frontend_scripts(){
		global $post;

		if(is_a( $post, 'WP_Post' ) && has_shortcode($post->post_content, 'shapla_testimonials')) {
			wp_enqueue_style( 'owl-carousel' );
			wp_enqueue_style( 'owl-carousel-theme' );
			wp_enqueue_script( 'owl-carousel' );
	    }
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

	public function add_mce_button() {
	    // check user permissions
	    if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
	        return;
	    }
	    // check if WYSIWYG is enabled
	    if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array ($this, 'add_tinymce_plugin') );
			add_filter( 'mce_buttons', array ($this, 'register_mce_button') );
	    }
	}
	public function add_tinymce_plugin( $plugin_array ) {
		global $shaplatools;
	    $plugin_array['shaplatools_testimonial_mce_button'] = $shaplatools->plugin_url() .'/assets/mce-button/mce-testimonial.js';
	    return $plugin_array;
	}

	public function register_mce_button( $buttons ) {
	    array_push( $buttons, 'shaplatools_testimonial_mce_button' );
	    return $buttons;
	}

	public function client_avatar(){
		remove_meta_box( 'postimagediv', 'testimonial', 'side' );
		add_meta_box('postimagediv', __('Client\'s Avatar', 'shapla'), 'post_thumbnail_meta_box', 'testimonial', 'side', 'low'		);
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box() {
		$meta_box = array(
		    'id' => 'shapla-metabox-testimonial',
		    'title' => __('Testimonial Details', 'shapla'),
		    'description' => __('Here you can customize your testimonial details.', 'shapla'),
		    'page' => 'testimonial',
		    'context' => 'normal',
		    'priority' => 'high',
		    'fields' => array(
		        array(
		            'name' => __('Client Name', 'shapla'),
		            'desc' => __('Enter the client name', 'shapla'),
		            'id' => '_shapla_testimonial_client',
		            'type' => 'text',
		            'std' => ''
		        ),
		        array(
		            'name' => __('Business/Site Name (optional)', 'shapla'),
		            'desc' => __('Enter the client business/site name', 'shapla'),
		            'id' => '_shapla_testimonial_source',
		            'type' => 'text',
		            'std' => '',
		        ),
		        array(
		            'name' => __('Business/Site Link (optional)', 'shapla'),
		            'desc' => __('Enter the project URL', 'shapla'),
		            'id' => '_shapla_testimonial_url',
		            'type' => 'text',
		            'std' => ''
		        ),
		    )
		);
		$ShaplaTools_Metaboxs = new ShaplaTools_Metaboxs();
		$ShaplaTools_Metaboxs->shapla_add_meta_box($meta_box);
	}

	public function columns_head( $columns ) {
		$columns = array(
			'cb' => '<input type="checkbox">',
			'title' => __('Title', 'shapla'),
			'testimonial' => __('Testimonial', 'shapla'),
			'testimonial-client-name' => __('Client\'s Name', 'shapla'),
			'testimonial-source' => __('Business/Site', 'shapla'),
			'testimonial-link' => __('Link', 'shapla'),
			'testimonial-avatar' => __('Client\'s Avatar', 'shapla')
		);

		return $columns;
	}

	public function columns_content( $column ) {

		$client = get_post_meta( get_the_ID(), '_shapla_testimonial_client', true );
		$source = get_post_meta( get_the_ID(), '_shapla_testimonial_source', true );
		$url = get_post_meta( get_the_ID(), '_shapla_testimonial_url', true );

		switch ( $column ) {
			case 'testimonial':
				the_excerpt();
				break;
			case 'testimonial-client-name':
				if ( ! empty( $client ) )
					echo $client;
				break;
			case 'testimonial-source':
				if ( ! empty( $source ) )
					echo $source;
				break;
			case 'testimonial-link':
				if ( ! empty( $url ) )
					echo $url;
				break;
			case 'testimonial-avatar':
				echo get_the_post_thumbnail( get_the_ID(), array(64,64));
				break;
		}
	}
}

function run_shaplatools_testimonial_meta(){
	ShaplaTools_Testimonial_Metabox::get_instance();
}
//run_shaplatools_testimonial_meta();
endif;