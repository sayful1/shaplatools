<?php

if( !class_exists('ShaplaTools_Portfolio_Metabox') ):

class ShaplaTools_Portfolio_Metabox {

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
		add_action( 'add_meta_boxes', array( $this, 'portfolio_image' ) );
		
		add_action( 'admin_head', array ($this, 'add_mce_button') );
		add_action( 'admin_enqueue_scripts', array ($this, 'admin_style') );

		add_filter( 'manage_edit-portfolio_columns', array ($this, 'columns_head') );
		add_action( 'manage_portfolio_posts_custom_column', array ($this, 'columns_content') );
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

	public function admin_style() {

        wp_localize_script( 'jquery', 'shapla_portfolio', $this->available_img_size() );
	}

	public function available_img_size(){
	    $shaplatools_img_size = get_intermediate_image_sizes();
	    array_push($shaplatools_img_size, 'full');

	    $result = array();

	    foreach ($shaplatools_img_size as $key => $value){

	        $result[]  = $value;
	    }

	    return $result;
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
	    $plugin_array['shaplatools_portfolio_mce_button'] = plugin_dir_url( dirname(dirname(__FILE__)) ) .'/assets/mce-button/mce-portfolio.js';
	    return $plugin_array;
	}

	public function register_mce_button( $buttons ) {
	    array_push( $buttons, 'shaplatools_portfolio_mce_button' );
	    return $buttons;
	}

	public function portfolio_image(){
		remove_meta_box( 'postimagediv', 'portfolio', 'side' );
		add_meta_box('postimagediv', __('Portfolio Featured Image', 'shapla'), 'post_thumbnail_meta_box', 'portfolio', 'side', 'low'		);
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box() {
		$meta_box = array(
		    'id' => 'shapla-metabox-portfolio',
		    'title' => __('Portfolio Settings', 'shapla'),
		    'description' => __('Here you can customize your project details.', 'shapla'),
		    'page' => 'portfolio',
		    'context' => 'normal',
		    'priority' => 'high',
		    'fields' => array(
		        array(
		            'name' => __('Project Images', 'shapla'),
		            'desc' => __('Choose project images.', 'shapla'),
		            'id' => '_shapla_portfolio_images',
		            'type' => 'images',
		            'std' => __('Upload Images', 'shapla')
		        ),
		        array(
		            'name' => __('Subtitle', 'shapla'),
		            'desc' => __('Enter the subtitle for this portfolio item', 'shapla'),
		            'id' => '_shapla_portfolio_subtitle',
		            'type' => 'text',
		            'std' => ''
		        ),
		        array(
		            'name' => __('Client Name', 'shapla'),
		            'desc' => __('Enter the client name of the project', 'shapla'),
		            'id' => '_shapla_portfolio_client',
		            'type' => 'text',
		            'std' => ''
		        ),
		        array(
		            'name' => __('Project Date', 'shapla'),
		            'desc' => __('Choose the project date.', 'shapla'),
		            'id' => '_shapla_portfolio_date',
		            'type' => 'text',
		            'std' => '',
		        ),
		        array(
		            'name' => __('Project URL', 'shapla'),
		            'desc' => __('Enter the project URL', 'shapla'),
		            'id' => '_shapla_portfolio_url',
		            'type' => 'text',
		            'std' => ''
		        ),
		    )
		);
		$ShaplaTools_Metaboxs = new ShaplaTools_Metaboxs();
		$ShaplaTools_Metaboxs->shapla_add_meta_box($meta_box);
	}

	public function columns_head( $defaults ) {
		unset( $defaults['date'] );

		$defaults['skill'] 			= __( 'Skills', 'shapla' );
		$defaults['project_date'] 	= __( 'Project Date', 'shapla' );
		$defaults['project_client'] = __( 'Client', 'shapla' );
		$defaults['project_url'] 	= __( 'Project URL', 'shapla' );

		return $defaults;
	}

	public function columns_content( $column_name ) {

		$date = strtotime(get_post_meta( get_the_ID(), '_shapla_portfolio_date', true ));
		$client = get_post_meta( get_the_ID(), '_shapla_portfolio_client', true );
		$url = get_post_meta( get_the_ID(), '_shapla_portfolio_url', true );

		if ( 'project_date' == $column_name ) {

			if (! empty( $date ))
				echo date_i18n( get_option( 'date_format' ), $date );
		}

		if ( 'skill' == $column_name ) {

			if ( ! $terms = get_the_terms( get_the_ID(), $column_name ) ) {
				echo '<span class="na">&mdash;</span>';
			} else {
				foreach ( $terms as $term ) {
					$termlist[] = '<a href="' . esc_url( add_query_arg( $column_name, $term->slug, admin_url( 'edit.php?post_type=portfolio' ) ) ) . ' ">' . ucfirst( $term->name ) . '</a>';
				}

				echo implode( ', ', $termlist );
			}
		}

		if ( 'project_client' == $column_name ) {
			if ( ! empty( $client ) )
			echo $client;
		}

		if ( 'project_url' == $column_name ) {
			if ( ! empty( $url ) )
			echo $url;
		}
	}
}

function run_shaplatools_portfolio_meta(){
	if (is_admin())
		ShaplaTools_Portfolio_Metabox::get_instance();
}
//run_shaplatools_portfolio_meta();
endif;