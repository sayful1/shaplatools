<?php

if( !class_exists('ShaplaTools_Event_Metabox') ):

class ShaplaTools_Event_Metabox {

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
		add_action( 'add_meta_boxes', array( $this, 'event_image' ) );

		add_filter( 'manage_edit-event_columns', array ($this, 'columns_head') );
		add_action( 'manage_event_posts_custom_column', array ($this, 'columns_content') );
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

	public function event_image(){
		remove_meta_box( 'postimagediv', 'event', 'side' );
		add_meta_box('postimagediv', __('Event Featured Image', 'shapla'), 'post_thumbnail_meta_box', 'event', 'side', 'low'		);
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box() {
		$meta_box = array(
		    'id' => 'shapla-metabox-event',
		    'title' => __('Event Info', 'shapla'),
		    'description' => __('Here you can customize your event details.', 'shapla'),
		    'page' => 'event',
		    'context' => 'normal',
		    'priority' => 'high',
		    'fields' => array(
		        array(
		            'name' => __('Event Images', 'shapla'),
		            'desc' => __('Choose event images.', 'shapla'),
		            'id' => '_shapla_event_images',
		            'type' => 'images',
		            'std' => __('Upload Images', 'shapla')
		        ),
		        array(
		            'name' => __('Event Start Date:', 'shapla'),
		            'desc' => __('Choose the event start date', 'shapla'),
		            'id' => '_shapla_event_start',
		            'type' => 'text',
		            'std' => ''
		        ),
		        array(
		            'name' => __('Event End Date:', 'shapla'),
		            'desc' => __('Choose the event end date', 'shapla'),
		            'id' => '_shapla_event_end',
		            'type' => 'text',
		            'std' => ''
		        ),
		        array(
		            'name' => __('Event Venue:', 'shapla'),
		            'desc' => __('Enter event address', 'shapla'),
		            'id' => '_shapla_event_venue',
		            'type' => 'text',
		            'std' => '',
		        ),
		    )
		);
		$ShaplaTools_Metaboxs = new ShaplaTools_Metaboxs();
		$ShaplaTools_Metaboxs->shapla_add_meta_box($meta_box);
	}

	public function columns_head( $defaults ) {
		unset( $defaults['date'] );

		$defaults['start_date'] = __( 'Start Date', 'shapla' );
		$defaults['end_date'] = __( 'End Date', 'shapla' );
		$defaults['venue'] = __( 'Venue', 'shapla' );

		return $defaults;
	}

	public function columns_content( $column_name ) {

		$start_date = strtotime( get_post_meta( get_the_ID(), '_shapla_event_start', true ) );
		$end_date 	= strtotime( get_post_meta( get_the_ID(), '_shapla_event_end', true ) );
		$venue 		= get_post_meta( get_the_ID(), '_shapla_event_venue', true );

		if ( 'start_date' == $column_name ) {

			if ( ! empty( $start_date ) )
				echo date_i18n( get_option( 'date_format' ), $start_date );
		}

		if ( 'end_date' == $column_name ) {

			if ( ! empty( $end_date ) )
				echo date_i18n( get_option( 'date_format' ), $end_date );
		}

		if ( 'venue' == $column_name ) {

			if ( ! empty( $venue ) )
			echo $venue;
		}
	}
}

function run_shaplatools_event_meta(){
	if (is_admin())
		ShaplaTools_Event_Metabox::get_instance();
}
//run_shaplatools_event_meta();
endif;