<?php
/**
 * Admin Options Page.
 *
 * @package ShaplaTools
 * @since 1.2
 * @access private
 * @return void
 */

/**
 * Get all settings.
 *
 * @return arran An array containing settings.
 */
function shaplatools_get_settings() {
	$settings = get_option( 'shaplatools_options' );

	if( empty( $settings ) ) {
		$general_settings = is_array( get_option( 'shaplatools_settings_general' ) ) ? get_option( 'shaplatools_settings_general' ) : array();
		$social_settings  = is_array( get_option( 'shaplatools_settings_social' ) ) ? get_option( 'shaplatools_settings_social' ) : array();

		$settings = array_merge( $general_settings, $social_settings );

		update_option( 'shaplatools_options', $settings );
	}

	return apply_filters( 'shaplatools_get_settings', $settings );
}

function shaplatools_options_page() {
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

	ob_start(); ?>

	<div class="wrap">
		<h2 class="nav-tab-wrapper">
			<?php
			foreach( shaplatools_get_settings_tabs() as $tab_id => $tab_name ) {

				$tab_url = add_query_arg( array(
					'settings-updated' => false,
					'tab'              => $tab_id
				) );

				$is_showing = true;

				if ( 'portfolio' == $tab_id ) {
					$is_showing = false;
					if ( current_theme_supports( 'post-type',  array( 'portfolio' ) ) || current_theme_supports( 'shapla-portfolio' ) ) {
						$is_showing = true;
					}
				}

				if( $is_showing ) :

				$active = $active_tab == $tab_id ? ' nav-tab-active' : '';

				echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
					echo esc_html( $tab_name );
				echo '</a>';

				endif;
			}
			?>
		</h2>

		<div id="tab_container">
			<form method="post" action="options.php">
				<table class="form-table">
				<?php

				if ( $_GET['page'] == 'shaplatools' && isset($_GET['settings-updated']) && $_GET['settings-updated'] == "true" ) {
					flush_rewrite_rules();
				}

				settings_fields( 'shaplatools_options' );
				do_settings_fields( 'shaplatools_settings_' . $active_tab, 'shaplatools_settings_' . $active_tab );
				?>
				</table>
				<?php submit_button(); ?>
			</form>
		</div><!-- #tab_container -->
	</div><!-- .wrap -->

	<?php
	echo ob_get_clean();
}

/**
 * Get settings tabs.
 *
 * @return array An array containing tab names.
 */
function shaplatools_get_settings_tabs() {
	$tabs              = array();
	$tabs['general']   = __( 'General', 'shapla' );
	$tabs['social']    = __( 'Social', 'shapla' );
	$tabs['post_type'] = __( 'Custom Post Types', 'shapla' );

	return apply_filters( 'shaplatools_settings_tabs', $tabs );
}

/**
 * Validate user inputs upon save.
 *
 * @param  array  $input An array containing values to filter
 * @return array         Filtered values array
 */
function shaplatools_settings_sanitize( $input = array() ) {
	global $shaplatools_options;

	parse_str( $_POST['_wp_http_referer'], $referrer );

	$output    = array();
	$settings  = shaplatools_get_registered_settings();
	$tab       = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';
	$post_data = isset( $_POST[ 'shaplatools_settings_' . $tab ] ) ? $_POST[ 'shaplatools_settings_' . $tab ] : array();

	$input = apply_filters( 'shaplatools_settings_' . $tab . '_sanitize', $post_data );

	// Loop through each setting being saved and pass it through a sanitization filter
	foreach( $input as $key => $value ) {
		// Get the setting type (checkbox, select, etc)
		$type = isset( $settings[ $key ][ 'type' ] ) ? $settings[ $key ][ 'type' ] : false;

		if( $type ) {
			// Field type specific filter
			$output[ $key ] = apply_filters( 'shaplatools_settings_sanitize_' . $type, $value, $key );
		}

		// General filter
		$output[ $key ] = apply_filters( 'shaplatools_settings_sanitize', $value, $key );
	}

	// Loop through the whitelist and unset any that are empty for the tab being saved
	if( ! empty( $settings[ $tab ] ) ) {
		foreach( $settings[ $tab ] as $key => $value ) {

			// settings used to have numeric keys, now they have keys that match the option ID. This ensures both methods work
			if( is_numeric( $key ) ) {
				$key = $value['id'];
			}

			if( empty( $_POST[ 'shaplatools_settings_' . $tab ][ $key ] ) ) {
				unset( $shaplatools_options[ $key ] );
			}

		}
	}

	// Merge our new settings with the existing
	$output = array_merge( $shaplatools_options, $output );

	add_settings_error( 'shaplatools-notices', '', __( 'Settings Updated', 'shapla' ), 'updated' );

	return $output;
}

/**
 * Register settings fields.
 *
 * Fires upon admin_init.
 *
 * @return void
 */
function shaplatools_register_settings() {
	if ( false == get_option( 'shaplatools_options' ) ) {
		add_option( 'shaplatools_options' );
	}

	foreach ( shaplatools_get_registered_settings() as $tab => $settings ) {
		add_settings_section(
			'shaplatools_settings_' . $tab,
			__return_null(),
			'__return_false',
			'shaplatools_settings_' . $tab
		);

		foreach ( $settings as $option ) {

			add_settings_field(
				'shaplatools_settings[' . $option['id'] . ']',
				$option['name'],
				function_exists( 'shaplatools_' . $option['type'] . '_callback' ) ? 'shaplatools_' . $option['type'] . '_callback' : 'shaplatools_missing_callback',
				'shaplatools_settings_' . $tab,
				'shaplatools_settings_' . $tab,
				array(
					'id'      => $option['id'],
					'desc'    => ! empty( $option['desc'] ) ? $option['desc'] : '',
					'name'    => $option['name'],
					'section' => $tab,
					'size'    => isset( $option['size'] ) ? $option['size'] : null,
					'options' => isset( $option['options'] ) ? $option['options'] : '',
					'std'     => isset( $option['std'] ) ? $option['std'] : '',
				)
			);
		}
	}

	register_setting( 'shaplatools_options', 'shaplatools_options', 'shaplatools_settings_sanitize' );
}
add_action( 'admin_init', 'shaplatools_register_settings' );

/**
 * Register all settings.
 *
 * @return array An array containing all settings.
 */
function shaplatools_get_registered_settings() {
	$shapla_settings = array(
		'general' => apply_filters( 'shaplatools_general_settings',
			array(
				'general_settings' => array(
					'id'   => 'general_settings',
					'name' => '<strong>' . __( 'General Settings', 'shapla' ) . '</strong>',
					'desc' => __( 'Configure the general settings', 'shapla' ),
					'type' => 'header'
				),
				'google_analytics' => array(
					'id'   => 'google_analytics',
					'name' => __( 'Google Analytics ID', 'shapla' ),
					'desc' => __( 'Enter Google Analytics ID like (UA-XXXXX-X)', 'shapla' ),
					'type' => 'text'
				),
				'typeahead_search' => array(
					'id'   => 'typeahead_search',
					'name' => __( 'Autocomplete search form', 'shapla' ),
					'desc' => __( 'Enable autocomplete search with twitter typeahead', 'shapla' ),
					'type' => 'select',
					'std'  => 'no_search',
					'options' => array(
						'no_search' => "Do not enable autocomplete search",
						'default_search' => "Enable for WordPress Default Search",
						'product_search' => "Enable for WooCommerce Product Search"
					)
				),
				'retina_graphics' => array(
					'id'   => 'retina_graphics',
					'name' => __( 'Retina graphics for your website', 'shapla' ),
					'desc' => __( 'Check to enable retina graphics for your website.', 'shapla' ),
					'type' => 'checkbox',
					'std'  => ''
				),
			)
		),
		'social' => apply_filters( 'shaplatools_social_settings',
			array(
				'android' => array(
					'id'   => 'android',
					'name' => 'Android',
					'desc' => '',
					'type' => 'url'
				),
				'apple' => array(
					'id'   => 'apple',
					'name' => 'Apple',
					'desc' => '',
					'type' => 'url'
				),
				'behance' => array(
					'id'   => 'behance',
					'name' => 'Behance',
					'desc' => 'e.g. https://www.behance.net/username',
					'type' => 'url'
				),
				'bitbucket' => array(
					'id'   => 'bitbucket',
					'name' => 'Bitbucket',
					'desc' => 'e.g. https://bitbucket.org/username',
					'type' => 'url'
				),
				'codepen' => array(
					'id'   => 'codepen',
					'name' => 'CodePen',
					'desc' => 'e.g. http://codepen.io/username',
					'type' => 'url'
				),
				'deviantart' => array(
					'id'   => 'deviantart',
					'name' => 'Deviant Art',
					'desc' => 'e.g. http://username.deviantart.com',
					'type' => 'url'
				),
				'dribbble' => array(
					'id'   => 'dribbble',
					'name' => 'Dribbble',
					'desc' => 'e.g. http://dribbble.com/username',
					'type' => 'url'
				),
				'dropbox' => array(
					'id'   => 'dropbox',
					'name' => 'Dropbox',
					'desc' => '',
					'type' => 'url'
				),
				'facebook' => array(
					'id'   => 'facebook',
					'name' => 'Facebook',
					'desc' => 'e.g. http://www.facebook.com/username',
					'type' => 'url'
				),
				'flickr' => array(
					'id'   => 'flickr',
					'name' => 'Flickr',
					'desc' => 'e.g. http://www.flickr.com/photos/username',
					'type' => 'url'
				),
				'foursquare' => array(
					'id'   => 'foursquare',
					'name' => 'Foursquare',
					'desc' => 'e.g. https://foursquare.com/username',
					'type' => 'url'
				),
				'github' => array(
					'id'   => 'github',
					'name' => 'GitHub',
					'desc' => 'e.g. https://github.com/username',
					'type' => 'url'
				),
				'google-plus' => array(
					'id'   => 'google-plus',
					'name' => 'Google+',
					'desc' => 'e.g. https://plus.google.com/userID',
					'type' => 'url'
				),
				'inshaplaram' => array(
					'id'   => 'inshaplaram',
					'name' => 'Inshaplaram',
					'desc' => 'e.g. http://inshaplaram.com/username',
					'type' => 'url'
				),
				'linkedin' => array(
					'id'   => 'linkedin',
					'name' => 'LinkedIn',
					'desc' => 'e.g. http://www.linkedin.com/in/username',
					'type' => 'url'
				),
				'mail' => array(
					'id'   => 'mail',
					'name' => 'Mail',
					'desc' => 'e.g. mailto:user@name.com',
					'type' => 'url'
				),
				'pinterest' => array(
					'id'   => 'pinterest',
					'name' => 'Pinterest',
					'desc' => 'e.g. http://pinterest.com/username',
					'type' => 'url'
				),
				'rss' => array(
					'id'   => 'rss',
					'name' => 'RSS',
					'desc' => 'e.g. http://example.com/feed',
					'type' => 'url',
					'std'  => get_bloginfo('rss2_url')
				),
				'skype' => array(
					'id'   => 'skype',
					'name' => 'Skype',
					'desc' => '',
					'type' => 'url'
				),
				'stack-exchange' => array(
					'id'   => 'stack-exchange',
					'name' => 'Stack Exchange',
					'desc' => 'http://stackexchange.com/users/userID',
					'type' => 'url'
				),
				'stack-overflow' => array(
					'id'   => 'stack-overflow',
					'name' => 'Stack Overflow',
					'desc' => 'e.g. http://stackoverflow.com/users/userID',
					'type' => 'url'
				),
				'trello' => array(
					'id'   => 'trello',
					'name' => 'Trello',
					'desc' => 'e.g. https://trello.com/username',
					'type' => 'url'
				),
				'tumblr' => array(
					'id'   => 'tumblr',
					'name' => 'Tumblr',
					'desc' => 'e.g. http://username.tumblr.com',
					'type' => 'url'
				),
				'twitter' => array(
					'id'   => 'twitter',
					'name' => 'Twitter',
					'desc' => 'e.g. http://twitter.com/username',
					'type' => 'url'
				),
				'vimeo' => array(
					'id'   => 'vimeo',
					'name' => 'Vimeo',
					'desc' => 'e.g. https://vimeo.com/username',
					'type' => 'url'
				),
				'vine' => array(
					'id'   => 'vine',
					'name' => 'Vine',
					'desc' => 'e.g. https://vine.co/username',
					'type' => 'url'
				),
				'windows' => array(
					'id'   => 'windows',
					'name' => 'Windows',
					'desc' => '',
					'type' => 'url'
				),
				'wordpress' => array(
					'id'   => 'wordpress',
					'name' => 'WordPress',
					'desc' => 'e.g. https://profiles.wordpress.org/username',
					'type' => 'url'
				),
				'xing' => array(
					'id'   => 'xing',
					'name' => 'Xing',
					'desc' => '',
					'type' => 'url'
				),
				'youtube' => array(
					'id'   => 'youtube',
					'name' => 'YouTube',
					'desc' => 'e.g. http://www.youtube.com/user/username',
					'type' => 'url'
				),
			)
		),
		'post_type' => apply_filters( 'shaplatools_post_type_settings',
			array(
				'post_type_settings' => array(
					'id'   => 'post_type_settings',
					'name' => '<strong>' . __( 'Custom Post Types Settings', 'shapla' ) . '</strong>',
					'desc' => __( 'Configure the general settings', 'shapla' ),
					'type' => 'header'
				),
				'slide' => array(
					'id'   => 'slide',
					'name' => __( 'slide', 'shapla' ),
					'desc' => __( 'Check to enable custom post type <strong>slide</strong>.', 'shapla' ),
					'type' => 'checkbox',
					'std'  => ''
				),
				'portfolio' => array(
					'id'   => 'portfolio',
					'name' => __( 'portfolio', 'shapla' ),
					'desc' => __( 'Check to enable custom post type <strong>portfolio</strong>.', 'shapla' ),
					'type' => 'checkbox',
					'std'  => '',
				),
				'team' => array(
					'id'   => 'team',
					'name' => __( 'team', 'shapla' ),
					'desc' => __( 'Check to enable custom post type <strong>team</strong>.', 'shapla' ),
					'type' => 'checkbox',
					'std'  => '',
				),
				'testimonial' => array(
					'id'   => 'testimonial',
					'name' => __( 'testimonial', 'shapla' ),
					'desc' => __( 'Check to enable custom post type <strong>testimonial</strong>.', 'shapla' ),
					'type' => 'checkbox',
					'std'  => '',
				),
				'feature' => array(
					'id'   => 'feature',
					'name' => __( 'feature', 'shapla' ),
					'desc' => __( 'Check to enable custom post type <strong>feature</strong>.', 'shapla' ),
					'type' => 'checkbox',
					'std'  => '',
				),
				'meta_box_settings' => array(
					'id'   => 'meta_box_settings',
					'name' => '<strong>' . __( 'Default Meta Box Settings', 'shapla' ) . '</strong>',
					'desc' => __( 'Configure the general settings', 'shapla' ),
					'type' => 'header'
				),
				'slide_meta' => array(
					'id'   => 'slide_meta',
					'name' => __( 'slide default meta', 'shapla' ),
					'desc' => __( 'Check to enable default meta for custom post type <strong>slide</strong>.', 'shapla' ),
					'type' => 'checkbox',
					'std'  => ''
				),
				'portfolio_meta' => array(
					'id'   => 'portfolio_meta',
					'name' => __( 'portfolio default meta', 'shapla' ),
					'desc' => __( 'Check to enable default meta for custom post type <strong>portfolio</strong>.', 'shapla' ),
					'type' => 'checkbox',
					'std'  => '',
				),
				'team_meta' => array(
					'id'   => 'team_meta',
					'name' => __( 'team default meta', 'shapla' ),
					'desc' => __( 'Check to enable default meta for custom post type <strong>team</strong>.', 'shapla' ),
					'type' => 'checkbox',
					'std'  => '',
				),
				'testimonial_meta' => array(
					'id'   => 'testimonial_meta',
					'name' => __( 'testimonial default meta', 'shapla' ),
					'desc' => __( 'Check to enable default meta for custom post type <strong>testimonial</strong>.', 'shapla' ),
					'type' => 'checkbox',
					'std'  => '',
				),
				'feature_meta' => array(
					'id'   => 'feature_meta',
					'name' => __( 'feature default meta', 'shapla' ),
					'desc' => __( 'Check to enable default meta for custom post type <strong>feature</strong>.', 'shapla' ),
					'type' => 'checkbox',
					'std'  => '',
				)
			)
		)
	);

	return $shapla_settings;
}


function shaplatools_missing_callback( $args ) {
	printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'shapla' ), $args['id'] );
}

/**
 * Text callback.
 *
 * Renders text fields.
 *
 * @param  array $args Arguments passed by the setting
 * @global $shaplatools_options Array of all ShaplaTools options
 * @return void
 */
function shaplatools_text_callback( $args ) {
	global $shaplatools_options;

	if ( isset( $shaplatools_options[ $args['id'] ] ) )
		$value = $shaplatools_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';

	$html = '<input type="text" class="' . $size . '-text" id="shaplatools_settings_' . $args['section'] . '[' . $args['id'] . ']" name="shaplatools_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<p class="description"> '  . $args['desc'] . '</p>';

	echo $html;
}

/**
 * URL callback.
 *
 * Renders URL fields.
 *
 * @param  array $args Arguments passed by the setting
 * @global $shaplatools_options Array of all ShaplaTools options
 * @return void
 */
function shaplatools_url_callback( $args ) {
	global $shaplatools_options;

	if ( isset( $shaplatools_options[ $args['id'] ] ) )
		$value = $shaplatools_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = isset( $args['size'] ) && !is_null($args['size']) ? $args['size'] : 'regular';

	$html = '<input type="text" class="' . $size . '-text" id="shaplatools_settings_' . $args['section'] . '[' . $args['id'] . ']" name="shaplatools_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_url( $value ) . '"/>';
	$html .= '<p class="description"> '  . $args['desc'] . '</p>';

	echo $html;
}

/**
 * Select callback.
 *
 * Renders select fields.
 *
 * @param  array $args Arguments passed by the setting
 * @global $shaplatools_options Array of all ShaplaTools options
 * @return void
 */
function shaplatools_select_callback( $args ) {
	global $shaplatools_options;

	if ( isset( $shaplatools_options[ $args['id'] ] ) )
		$value = $shaplatools_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$html = '<select id="shaplatools_settings_' . $args['section'] . '[' . $args['id'] . ']" name="shaplatools_settings_' . $args['section'] . '[' . $args['id'] . ']"/>';

	foreach ( $args['options'] as $option => $name ) :
		$selected = selected( $option, $value, false );
		$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
	endforeach;

	$html .= '</select>';
	$html .= '<p class="description"> '  . $args['desc'] . '</p>';

	echo $html;
}
function shaplatools_checkbox_callback( $args ) {
	global $shaplatools_options;

	if ( isset( $shaplatools_options[ $args['id'] ] ) ){
		$value = $shaplatools_options[ $args['id'] ];
	}
	else{
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	$checked = checked( $value, 'on', false );

	$html  = '<input type="checkbox" id="shaplatools_settings_' . $args['section'] . '[' . $args['id'] . ']" name="shaplatools_settings_' . $args['section'] . '[' . $args['id'] . ']" value="on"'.$checked.'>';

	$html .= '<p class="description"> '  . $args['desc'] . '</p>';

	echo $html;
}

/**
 * Header callback.
 *
 * Renders the header.
 *
 * @param  array $args Arguments passed by the setting
 * @return void
 */
function shaplatools_header_callback( $args ) {
	echo '';
}
