<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Shapla_Slider_Widget extends WP_Widget {

	private $slide_type = 'shaplatools_slide';

	/**
	 * Shapla_Slider_Widget constructor.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_shapla_slider',
			'description' => __( 'The easiest way to create image, video, post and WooCommerce product carousel.',
				'shaplatools' ),
		);
		parent::__construct( 'widget_shapla_slider', __( 'Shapla Slider', 'carousel-slider' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {

		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : null;
		$slider_id = isset( $instance['slider_id'] ) ? absint( $instance['slider_id'] ) : 0;

		if ( ! $slider_id ) {
			return;
		}

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo do_shortcode( '[shapla_slider id=' . $slider_id . ']' );
		echo $args['after_widget'];
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @param array $instance Current settings.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$carousels   = $this->carousels_list();
		$carousel_id = ! empty( $instance['slider_id'] ) ? absint( $instance['slider_id'] ) : 0;
		$title       = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';

		if ( count( $carousels ) > 0 ) {

			printf( '<p><label for="%1$s">%2$s</label>', $this->get_field_id( 'title' ),
				__( 'Title (optional):', 'carousel-slider' ) );
			printf( '<input type="text" class="widefat" id="%1$s" name="%2$s" value="%3$s" /></p>',
				$this->get_field_id( 'title' ), $this->get_field_name( 'title' ), $title );

			printf( '<p><label>%s</label>', __( 'Choose Slider', 'carousel-slider' ) );
			printf( '<select class="widefat" name="%s">', $this->get_field_name( 'slider_id' ) );
			foreach ( $carousels as $carousel ) {
				$selected = $carousel->id == $carousel_id ? 'selected="selected"' : '';
				printf(
					'<option value="%1$d" %3$s>%2$s</option>',
					$carousel->id,
					$carousel->title,
					$selected
				);
			}
			echo "</select></p>";

		} else {
			$slider_url = add_query_arg( array( 'post_type' => $this->slide_type ), admin_url( 'post-new.php' ) );
			printf( '<p>%1$s <a href="' . $slider_url . '">%3$s</a> %2$s</p>',
				__( 'You did not add any carousel slider yet.', 'carousel-slider' ),
				__( 'to create a new carousel slider now.', 'carousel-slider' ),
				__( 'click here', 'carousel-slider' )
			);
		}
	}

	/**
	 * Get the list of carousel sliders
	 *
	 * @return array
	 */
	private function carousels_list() {
		$carousels = get_posts( array(
			'post_type'   => $this->slide_type,
			'post_status' => 'publish',
		) );

		if ( count( $carousels ) < 1 ) {
			return array();
		}

		return array_map( function ( $carousel ) {
			return (object) array(
				'id'    => absint( $carousel->ID ),
				'title' => esc_html( $carousel->post_title ),
			);
		}, $carousels );
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$old_instance['title']     = sanitize_text_field( $new_instance['title'] );
		$old_instance['slider_id'] = absint( $new_instance['slider_id'] );

		return $old_instance;
	}
}

add_action( 'widgets_init', function () {
	register_widget( 'Shapla_Slider_Widget' );
} );
