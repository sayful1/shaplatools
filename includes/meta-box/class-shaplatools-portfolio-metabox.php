<?php

if ( ! class_exists( 'ShaplaTools_Portfolio_Metabox' ) ) {

	class ShaplaTools_Portfolio_Metabox {

		private static $instance = null;

		/**
		 * @return ShaplaTools_Portfolio_Metabox
		 */
		public static function instance() {
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
			add_action( 'add_meta_boxes', array( $this, 'portfolio_image' ) );

			add_filter( 'manage_edit-portfolio_columns', array( $this, 'columns_head' ) );
			add_action( 'manage_portfolio_posts_custom_column', array( $this, 'columns_content' ) );
		}

		public function available_img_size() {
			$shaplatools_img_size = get_intermediate_image_sizes();
			array_push( $shaplatools_img_size, 'full' );

			$result = array();

			foreach ( $shaplatools_img_size as $key => $value ) {

				$result[] = $value;
			}

			return $result;
		}

		public function portfolio_image() {
			remove_meta_box( 'postimagediv', 'portfolio', 'side' );
			add_meta_box( 'postimagediv', __( 'Portfolio Featured Image', 'shaplatools' ), 'post_thumbnail_meta_box', 'portfolio', 'side', 'low' );
		}

		/**
		 * Adds the meta box container.
		 */
		public function add_meta_box() {
			$meta_box             = array(
				'id'          => 'shapla-metabox-portfolio',
				'title'       => __( 'Portfolio Settings', 'shaplatools' ),
				'description' => __( 'Here you can customize your project details.', 'shaplatools' ),
				'screen'      => 'portfolio',
				'context'     => 'normal',
				'priority'    => 'high',
				'fields'      => array(
					array(
						'name' => __( 'Project Images', 'shaplatools' ),
						'desc' => __( 'Choose project images.', 'shaplatools' ),
						'id'   => '_shapla_portfolio_images',
						'type' => 'images',
						'std'  => __( 'Upload Images', 'shaplatools' )
					),
					array(
						'name' => __( 'Subtitle', 'shaplatools' ),
						'desc' => __( 'Enter the subtitle for this portfolio item', 'shaplatools' ),
						'id'   => '_shapla_portfolio_subtitle',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __( 'Client Name', 'shaplatools' ),
						'desc' => __( 'Enter the client name of the project', 'shaplatools' ),
						'id'   => '_shapla_portfolio_client',
						'type' => 'text',
						'std'  => ''
					),
					array(
						'name' => __( 'Project Date', 'shaplatools' ),
						'desc' => __( 'Choose the project date.', 'shaplatools' ),
						'id'   => '_shapla_portfolio_date',
						'type' => 'date',
						'std'  => '',
					),
					array(
						'name' => __( 'Project URL', 'shaplatools' ),
						'desc' => __( 'Enter the project URL', 'shaplatools' ),
						'id'   => '_shapla_portfolio_url',
						'type' => 'text',
						'std'  => ''
					),
				)
			);
			$ShaplaTools_Metaboxs = new ShaplaTools_Meta_Box;
			$ShaplaTools_Metaboxs->add( $meta_box );
		}

		public function columns_head( $defaults ) {
			unset( $defaults['date'] );

			$defaults['skill']          = __( 'Skills', 'shaplatools' );
			$defaults['project_date']   = __( 'Project Date', 'shaplatools' );
			$defaults['project_client'] = __( 'Client', 'shaplatools' );
			$defaults['project_url']    = __( 'Project URL', 'shaplatools' );

			return $defaults;
		}

		public function columns_content( $column_name ) {

			$date   = strtotime( get_post_meta( get_the_ID(), '_shapla_portfolio_date', true ) );
			$client = get_post_meta( get_the_ID(), '_shapla_portfolio_client', true );
			$url    = get_post_meta( get_the_ID(), '_shapla_portfolio_url', true );

			if ( 'project_date' == $column_name ) {

				if ( ! empty( $date ) ) {
					echo date_i18n( get_option( 'date_format' ), $date );
				}
			}

			if ( 'skill' == $column_name ) {

				if ( ! $terms = get_the_terms( get_the_ID(), $column_name ) ) {
					echo '<span class="na">&mdash;</span>';
				} else {
					$termlist = array();
					foreach ( $terms as $term ) {
						$termlist[] = '<a href="' . esc_url( add_query_arg( $column_name, $term->slug, admin_url( 'edit.php?post_type=portfolio' ) ) ) . ' ">' . ucfirst( $term->name ) . '</a>';
					}

					echo implode( ', ', $termlist );
				}
			}

			if ( 'project_client' == $column_name ) {
				if ( ! empty( $client ) ) {
					echo $client;
				}
			}

			if ( 'project_url' == $column_name ) {
				if ( ! empty( $url ) ) {
					echo $url;
				}
			}
		}
	}
}

ShaplaTools_Portfolio_Metabox::instance();
