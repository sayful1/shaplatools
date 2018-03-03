<?php

if ( ! class_exists( 'ShaplaTools_Meta_Box' ) ):

	class ShaplaTools_Meta_Box {

		private static $instance;

		/**
		 * @return ShaplaTools_Meta_Box
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				add_action( 'admin_head', array( __CLASS__, 'meta_box_style' ) );
			}

			return self::$instance;
		}

		/**
		 * Meta box style
		 */
		public static function meta_box_style() {
			?>
            <style type="text/css">
                .shapla-metabox-table {
                    border-collapse: collapse;
                    width: 100%
                }

                .shapla-metabox-table tr {
                    border-top: 1px solid #ececec
                }

                .shapla-metabox-table tr:first-child {
                    border-top: none
                }

                .shapla-metabox-table th {
                    text-align: left;
                    width: 40%;
                    padding: 10px 0;
                    vertical-align: top
                }

                .shapla-metabox-table th label {
                    text-shadow: white 0 1px 0
                }

                .shapla-metabox-table th label strong {
                    font-weight: 500;
                    color: #444
                }

                .shapla-metabox-table th label span {
                    display: block;
                    font-size: 12px;
                    font-weight: normal;
                    color: #999;
                    margin: 8px 0 0 0
                }

                .shapla-metabox-table td {
                    padding: 8px 10px
                }

                #side-sortables .shapla-metabox-table th,
                #side-sortables .shapla-metabox-table td {
                    width: 100%;
                    display: block;
                    padding-left: 0;
                    padding-right: 0
                }

                #side-sortables .shapla-metabox-table td {
                    border-bottom: none
                }
            </style>
			<?php
		}

		/**
		 * ShaplaTools_Meta_Box constructor.
		 */
		public function __construct() {
			add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
			add_action( 'wp_ajax_shaplatools_save_images', array( $this, 'save_images' ) );
		}

		/**
		 * Save custom meta box
		 *
		 * @param int $post_id The post ID
		 */
		public function save_meta_boxes( $post_id ) {

			// Verify that the nonce is valid.
			$nonce = isset( $_POST['_shaplatools_nonce'] ) && wp_verify_nonce( $_POST['_shaplatools_nonce'], basename( __FILE__ ) );
			if ( ! $nonce ) {
				return;
			}

			// Check if not an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return;
			}

			// Check if not a revision.
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}

			// Check if user has permissions to save data.
			$capability = ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) ? 'edit_page' : 'edit_post';
			if ( ! current_user_can( $capability, $post_id ) ) {
				return;
			}

			// Check if meta box data exists
			if ( ! isset( $_POST['shapla_meta'] ) ) {
				return;
			}

			foreach ( $_POST['shapla_meta'] as $key => $val ) {
				update_post_meta( $post_id, $key, $this->sanitize_value( $val ) );
			}
		}

		/**
		 * Sanitize meta value
		 *
		 * @param $input
		 *
		 * @return array|string
		 */
		private function sanitize_value( $input ) {
			// Initialize the new array that will hold the sanitize values
			$new_input = array();

			if ( is_array( $input ) ) {
				// Loop through the input and sanitize each of the values
				foreach ( $input as $key => $value ) {
					if ( is_array( $value ) ) {
						$new_input[ $key ] = $this->sanitize_value( $value );
					} else {
						$new_input[ $key ] = sanitize_text_field( $value );
					}
				}
			} else {
				return sanitize_text_field( $input );
			}

			return $new_input;
		}

		public function save_images() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'shaplatools_nonce' ) ) {
				return;
			}

			if ( ! isset( $_POST['post_id'], $_POST['ids'] ) ) {
				return;
			}

			$post_id = $_POST['post_id'];
			// Check if user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			// Check if not an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return;
			}

			$ids = strip_tags( rtrim( $_POST['ids'], ',' ) );
			update_post_meta( $post_id, '_shaplatools_images_ids', $ids );

			$thumbs_output = '';
			foreach ( explode( ',', $ids ) as $thumb ) {
				$thumbs_output .= sprintf(
					'<li class="shaplatools_gallery_list_item">%s</li>',
					wp_get_attachment_image( $thumb, array( 75, 75 ) )
				);
			}
			echo $thumbs_output;
			wp_die();
		}

		/**
		 * Add a custom meta box
		 *
		 * @param array $meta_box Meta box input data
		 */
		public function add( $meta_box ) {
			if ( ! is_array( $meta_box ) ) {
				return;
			}

			add_meta_box(
				$meta_box['id'],
				$meta_box['title'],
				array( $this, 'meta_box_callback' ),
				$meta_box['screen'],
				$meta_box['context'] ?: 'advanced',
				$meta_box['priority'] ?: 'high',
				$meta_box
			);
		}

		/**
		 * Create content for the custom meta box
		 *
		 * @param  WP_Post $post
		 * @param  array $meta_box
		 *
		 * @return void
		 */
		public function meta_box_callback( $post, $meta_box ) {
			if ( ! is_array( $meta_box['args'] ) ) {
				return;
			}

			wp_nonce_field( basename( __FILE__ ), '_shaplatools_nonce' );

			$meta_box = $meta_box['args'];

			if ( isset( $meta_box['description'] ) && $meta_box['description'] != '' ) {
				echo sprintf( '<p class="description">%s</p>', $meta_box['description'] );
			}

			$table = "";
			$table .= "<table class='form-table shapla-metabox-table'>";

			foreach ( $meta_box['fields'] as $field ) {
				$std_value = isset( $field['std'] ) ? $field['std'] : '';
				$meta      = get_post_meta( $post->ID, $field['id'], true );
				$value     = $meta ? $meta : $std_value;
				$name      = sprintf( 'shapla_meta[%s]', $field['id'] );
				$type      = isset( $field['type'] ) ? $field['type'] : 'text';

				$table .= "<tr>";

				$table .= '<th>';

				$table .= '<label for="' . $field['id'] . '">';
				$table .= '<strong>' . $field['name'] . '</strong>';
				if ( ! empty( $field['desc'] ) ) {
					$table .= '<span>' . $field['desc'] . '</span>';
				}
				$table .= '</label>';
				$table .= '</th>';

				$table .= "<td>";

				if ( method_exists( $this, $type ) ) {
					$table .= $this->$type( $field, $name, $value );
				} else {
					$table .= $this->text( $field, $name, $value );
				}

				$table .= "</td>";
				$table .= "</tr>";
			}

			$table .= "</table>";
			echo $table;
		}

		/**
		 * text input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function text( $field, $name, $value ) {
			return sprintf( '<input type="text" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * email input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function email( $field, $name, $value ) {
			return sprintf( '<input type="email" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * password input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function password( $field, $name, $value ) {
			return sprintf( '<input type="password" class="regular-text" value="" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * number input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function number( $field, $name, $value ) {
			return sprintf( '<input type="number" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * url input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function url( $field, $name, $value ) {
			return sprintf( '<input type="url" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * color input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function color( $field, $name, $value ) {
			$default_color = ( isset( $field['std'] ) ) ? $field['std'] : "";

			return sprintf( '<input type="text" class="colorpicker" value="%1$s" id="%2$s" name="%3$s" data-default-color="%4$s">', $value, $field['id'], $name, $default_color );
		}

		/**
		 * date input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function date( $field, $name, $value ) {
			$value = date( "F d, Y", strtotime( $value ) );

			return sprintf( '<input type="text" class="regular-text datepicker" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * textarea input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function textarea( $field, $name, $value ) {
			$rows = ( isset( $field['rows'] ) ) ? $field['rows'] : 5;
			$cols = ( isset( $field['cols'] ) ) ? $field['cols'] : 40;

			return sprintf( '<textarea id="%2$s" name="%3$s" rows="%4$s" cols="%5$s">%1$s</textarea>', $value, $field['id'], $name, $rows, $cols );
		}

		/**
		 * checkbox input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function checkbox( $field, $name, $value ) {
			$checked = ( 1 == $value ) ? 'checked="checked"' : '';
			$table   = sprintf( '<input type="hidden" name="%1$s" value="0">', $name );
			$table   .= sprintf( '<fieldset><legend class="screen-reader-text"><span>%1$s</span></legend><label for="%2$s"><input type="checkbox" value="1" id="%2$s" name="%4$s" %3$s>%1$s</label></fieldset>', $field['name'], $field['id'], $checked, $name );

			return $table;
		}

		/**
		 * multi checkbox input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function multi_checkbox( $field, $name, $value ) {
			$table           = "<fieldset>";
			$multicheck_name = $name . "[]";

			$table .= sprintf( '<input type="hidden" name="%1$s" value="0">', $multicheck_name );
			foreach ( $field['options'] as $key => $label ) {
				$multichecked = ( in_array( $key, $this->options[ $field['id'] ] ) ) ? 'checked="checked"' : '';
				$table        .= sprintf( '<label for="%1$s"><input type="checkbox" value="%1$s" id="%1$s" name="%2$s" %3$s>%4$s</label><br>', $key, $multicheck_name, $multichecked, $label );
			}
			$table .= "</fieldset>";

			return $table;
		}

		/**
		 * radio input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function radio( $field, $name, $value ) {
			$table = sprintf( '<fieldset><legend class="screen-reader-text"><span>%1$s</span></legend><p>', $field['name'] );

			foreach ( $field['options'] as $key => $radio_label ) {

				$radio_checked = ( $value == $key ) ? 'checked="checked"' : '';
				$table         .= sprintf( '<label><input type="radio" %1$s value="%2$s" name="%3$s">%4$s</label><br>', $radio_checked, $key, $name, $radio_label );
			}
			$table .= "</p></fieldset>";

			return $table;
		}

		/**
		 * select input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function select( $field, $name, $value ) {
			$table = sprintf( '<select id="%1$s" name="%2$s">', $field['id'], $name );
			foreach ( $field['options'] as $key => $select_label ) {
				$selected = ( $value == $key ) ? 'selected="selected"' : '';
				$table    .= sprintf( '<option value="%1$s" %2$s>%3$s</option>', $key, $selected, $select_label );
			}
			$table .= "</select>";

			return $table;
		}

		/**
		 * wp_editor input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function wp_editor( $field, $name, $value ) {
			ob_start();
			echo "<div class='sp-wp-editor-container'>";
			wp_editor( $value, $field['id'], array(
				'textarea_name' => $name,
				'tinymce'       => false,
				'media_buttons' => false,
				'textarea_rows' => isset( $field['rows'] ) ? $field['rows'] : 6,
				'quicktags'     => array( "buttons" => "strong,em,link,img,ul,li,ol" ),
			) );
			echo "</div>";

			return ob_get_clean();
		}

		/**
		 * images input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		public function images( $field, $name, $value ) {
			$btn_text = $value ? 'Edit Gallery' : 'Add Gallery';
			$value    = strip_tags( rtrim( $value, ',' ) );
			$output   = '';

			if ( $value ) {
				$thumbs = explode( ',', $value );
				foreach ( $thumbs as $thumb ) {
					$output .= '<li class="shaplatools_gallery_list_item">' . wp_get_attachment_image( $thumb, array(
							75,
							75
						) ) . '</li>';
				}
			}

			$html = '';
			$html .= '<div class="shaplatools_gallery_images">';
			$html .= sprintf( '<input type="hidden" value="%1$s" id="shaplatools_images_ids" name="%2$s">', $value, $name );
			$html .= sprintf( '<a href="#" id="shaplatools_gallery_btn" class="shaplatools_gallery_btn">%s</a>', $btn_text );
			$html .= sprintf( '<ul class="shaplatools_gallery_list">%s</ul>', $output );
			$html .= '</div>';

			return $html;
		}
	}

endif;

if ( is_admin() ) {
	ShaplaTools_Meta_Box::instance();
}
