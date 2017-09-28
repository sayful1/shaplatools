<?php

add_action( 'widgets_init', create_function( '', 'return register_widget( "Shapla_Instagram_Widget" );' ) );

class Shapla_Instagram_Widget extends WP_Widget{

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'shapla-instagram', // Base ID
			__( 'Shapla Instagram Photos', 'shapla' ), // Name
			array( 'description' => __( 'A widget that displays your Instagram feed, posts, or likes.', 'shapla' ), ) // Args
		);
	}

	function widget( $args, $instance ) {
		extract( $args );

		echo $before_widget;

		$title     = apply_filters( 'widget_title', $instance['title'] );
		$username  = esc_html( $instance['username'] );
		$user_id   = absint( $instance['user_id'] );
		$client_id = esc_html( $instance['client_id'] );
		$count     = absint( $instance['count'] );
		$image_res = esc_html( $instance['size'] );
		$cachetime = absint( $instance['cachetime'] );

		// Get Instagrams
		$instagram = $this->get_instagrams( array(
			'user_id'   => $user_id,
			'client_id' => $client_id,
			'count'     => $count,
			'cachetime' => $cachetime,
		) );

		if ( $title ) echo $before_title . $title . $after_title;

		// And if we have Instagrams
		if ( false !== $instagram ) :

		?>

			<ul class="instagram-widget <?php echo esc_attr( $image_res ); ?>">
				<?php
					foreach ( $instagram['data'] as $key => $image ) {
						echo apply_filters( 'st_instagram_widget_image_html', sprintf( '<li><a href="%1$s"><img class="instagram-image" src="%2$s" alt="%3$s" title="%3$s" /></a></li>',
							$image['link'],
							str_replace( 'http:', '', $image['images'][ $image_res ]['url'] ),
							$image['caption']['text']
						), $image );
					}
				?>

			</ul>

			<a class="instagram-follow-link" href="https://instagram.com/<?php echo esc_html( $username ); ?>"><?php printf( __( 'Follow %1$s on Instagram', 'shapla' ), esc_html( $username ) ); ?></a>

		<?php elseif ( ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) && ( defined( 'WP_DEBUG_DISPLAY' ) && false !== WP_DEBUG_DISPLAY ) ) : ?>
			<div id="message" class="error"><p><?php _e( 'Error: We were unable to fetch your instagram feed.', 'shapla' ); ?></p></div>
		<?php endif;

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']     = esc_attr( $new_instance['title'] );
		$instance['username']  = esc_attr( $new_instance['username'] );
		$instance['user_id']   = absint( $new_instance['user_id'] );
		$instance['client_id'] = esc_html( $new_instance['client_id'] );
		$instance['size']      = esc_attr( $new_instance['size'] );
		$instance['cachetime'] = absint( $new_instance['cachetime'] );
		$instance['count']     = absint( $new_instance['count'] );

		return $instance;
	}

	function form( $instance ){
		$defaults = array(
			'title'     => __( 'Instagram Photos', 'shapla' ),
			'username'  => '',
			'user_id'  	=> '',
			'client_id' => '',
			'cachetime' => '2',
			'size'      => 'thumbnail',
			'count'     => 9,
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'shapla' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e( 'Instagram Username:', 'shapla' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" value="<?php echo $instance['username']; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('user_id'); ?>"><?php _e( 'User ID:', 'shapla' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('user_id'); ?>" name="<?php echo $this->get_field_name('user_id'); ?>" value="<?php echo $instance['user_id']; ?>">
			<span class="description"><?php printf( __( 'Lookup your User ID <a href="%s" target="_blank">here</a>', 'stag' ), 'http://jelled.com/instagram/lookup-user-id' ); ?></span>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('client_id'); ?>"><?php _e( 'Client ID:', 'shapla' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('client_id'); ?>" name="<?php echo $this->get_field_name('client_id'); ?>" value="<?php echo $instance['client_id']; ?>">
			<span class="description"><?php printf( __( 'Register a new client <a href="%s" target="_blank">here</a>', 'stag' ), 'http://instagram.com/developer/clients/manage/' ); ?></span>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Photo Count:', 'shapla' ); ?></label>
			<input type="number" min="1" max="20" class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" value="<?php echo $instance['count']; ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('size'); ?>"><?php _e( 'Photo Size:', 'shapla' ); ?></label>
			<select id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>" class="widefat">
				<option value="thumbnail" <?php selected('thumbnail', $instance['size']) ?>><?php _e('Thumbnail', 'stag'); ?></option>
				<option value="low_resolution" <?php selected('low_resolution', $instance['size']) ?>><?php _e('Low Resolution', 'shapla'); ?></option>
				<option value="standard_resolution" <?php selected('standard_resolution', $instance['size']) ?>><?php _e('High Resolution', 'shapla'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('cachetime'); ?>"><?php _e( 'Cache time (in hours):', 'shapla' ); ?></label>
			<input type="number" min="1" max="500" id="<?php echo $this->get_field_id('cachetime'); ?>" name="<?php echo $this->get_field_name('cachetime'); ?>" value="<?php echo $instance['cachetime']; ?>">
		</p>

		<?php
	}

	/**
	 * Get relevant data from Instagram API.
	 *
	 * @param	array $args Argument to passed to Instagram API.
	 * @return  array 		An array returning Instagram API data.
	 */
	public function get_instagrams( $args = array() ) {
		// Get args
		$user_id   = ( ! empty( $args['user_id'] ) ) ? $args['user_id'] : '';
		$client_id = ( ! empty( $args['client_id'] ) ) ? $args['client_id'] : '';
		$count     = ( ! empty( $args['count'] ) ) ? $args['count'] : 9;
		$cachetime = ( ! empty( $args['cachetime'] ) ) ? $args['cachetime'] : 2;

		// If no client id or user id, bail
		if ( empty( $client_id ) || empty( $user_id ) ) {
			return false;
		}

		$key = 'st_instagram_widget_' . $user_id;

		if ( false === ( $instagrams = get_transient( $key ) ) ) {
			// Ping Instragram's API
			$api_url = 'https://api.instagram.com/v1/users/' . esc_html( $user_id ) . '/media/recent/';
			$response = wp_remote_get( add_query_arg( array(
				'client_id' => esc_html( $client_id ),
				'count'     => absint( $count )
			), $api_url ) );

			// Check if the API is up.
			if ( ! 200 == wp_remote_retrieve_response_code( $response ) ) {
				return false;
			}

			// Parse the API data and place into an array
			$instagrams = json_decode( wp_remote_retrieve_body( $response ), true );

			// Are the results in an array?
			if ( ! is_array( $instagrams ) ) {
				return false;
			}

			$instagrams = maybe_unserialize( $instagrams );

			// Store Instagrams in a transient, and expire every hour
			set_transient( $key, $instagrams, $cachetime * HOUR_IN_SECONDS );
		}

		return $instagrams;
	}
}
