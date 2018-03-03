<?php
/**
 * Very simple WordPress Settings API wrapper class
 *
 * WordPress Option Page Wrapper class that implements WordPress Settings API and
 * give you easy way to create multi tabs admin menu and
 * add setting fields with build in validation.
 *
 * @author  Sayful Islam <sayful.islam001@gmail.com>
 * @link    https://sayfulislam.com
 */
if ( ! class_exists( 'ShaplaTools_Settings_API' ) ) {
	/**
	 * Class ShaplaTools_Settings_API
	 */
	class ShaplaTools_Settings_API {
		/**
		 * Settings options array
		 */
		private $options = array();

		/**
		 * Settings menu fields array
		 */
		private $menu_fields = array();

		/**
		 * Settings fields array
		 */
		private $fields = array();

		/**
		 * Settings tabs array
		 */
		private $panels = array();

		/**
		 * @var array
		 */
		private $sections = array();

		/**
		 * Initialization or class
		 */
		public function __construct() {
			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'admin_menu' ) );
				add_action( 'admin_init', array( $this, 'admin_init' ) );
			}
		}

		/**
		 * Add new admin menu
		 *
		 * This method is accessible outside the class for creating menu
		 *
		 * @param array $menu_fields
		 *
		 * @return WP_Error|ShaplaTools_Settings_API
		 */
		public function add_menu( array $menu_fields ) {
			if ( ! isset( $menu_fields['page_title'], $menu_fields['menu_title'], $menu_fields['menu_slug'] ) ) {
				return new WP_Error( 'field_not_set', 'Required key is not set properly for creating menu.' );
			}

			$this->menu_fields = $menu_fields;

			return $this;
		}

		/**
		 * Add setting page tab
		 *
		 * This method is accessible outside the class for creating page tab
		 *
		 * @param array $panel
		 *
		 * @return WP_Error|$this
		 */
		public function add_panel( array $panel ) {
			if ( ! isset( $panel['id'], $panel['title'] ) ) {
				return new WP_Error( 'field_not_set', 'Required key is not set properly for creating tab.' );
			}

			$this->panels[] = $panel;

			return $this;
		}

		/**
		 * Add Setting page section
		 *
		 * @param array $section
		 *
		 * @return $this
		 */
		public function add_section( array $section ) {

			$this->sections[] = $section;

			return $this;
		}

		/**
		 * Get sections for current panel
		 *
		 * @param string $panel
		 *
		 * @return array
		 */
		public function getSections( $panel = '' ) {
			$sections = [];

			foreach ( $this->sections as $section ) {
				$sections[] = wp_parse_args( $section, [
					'id'          => '',
					'panel'       => '',
					'title'       => '',
					'description' => '',
					'priority'    => 200,
				] );
			}

			// Sort by priority
			usort( $sections, function ( $a, $b ) {
				return $a['priority'] - $b['priority'];
			} );

			if ( empty( $panel ) ) {
				return $sections;
			}

			$current_panel = [];
			foreach ( $sections as $section ) {
				if ( $section['panel'] == $panel ) {
					$current_panel[] = $section;
				}
			}

			return $current_panel;
		}

		/**
		 * @param string $section
		 *
		 * @return mixed
		 */
		public function getFields( $section = '' ) {
			$fields = [];

			foreach ( $this->fields as $field ) {
				if ( ! isset( $field['priority'] ) ) {
					$field['priority'] = 200;
				}
				$fields[] = $field;
			}

			// Sort by priority
			usort( $fields, function ( $a, $b ) {
				return $a['priority'] - $b['priority'];
			} );

			if ( empty( $section ) ) {
				return $fields;
			}

			$current_field = [];
			foreach ( $fields as $field ) {
				if ( $field['section'] == $section ) {
					$current_field[] = $field;
				}
			}

			return $current_field;
		}

		/**
		 * Filter settings fields by page tab
		 *
		 * @param  string $current_tab
		 *
		 * @return array
		 */
		public function getFieldsByPanel( $current_tab = null ) {

			if ( ! $current_tab ) {
				$current_tab = isset ( $_GET['tab'] ) ? $_GET['tab'] : $this->panels[0]['id'];
			}

			$newarray = array();
			$sections = $this->getSections( $current_tab );

			foreach ( $sections as $section ) {
				$_section = $this->getFields( $section['id'] );
				$newarray = array_merge( $newarray, $_section );
			}

			return $newarray;
		}

		/**
		 * Add new settings field
		 *
		 * This method is accessible outside the class for creating settings field
		 *
		 * @param array $field
		 *
		 * @return WP_Error|$this
		 */
		public function add_field( array $field ) {
			if ( ! isset( $field['id'], $field['name'] ) ) {
				return new WP_Error( 'field_not_set', 'Required key is not set properly for creating tab.' );
			}

			$this->fields[] = $field;

			return $this;
		}

		/**
		 * Register setting and its sanitization callback.
		 * @return void
		 */
		public function admin_init() {
			register_setting(
				$this->menu_fields['option_name'],
				$this->menu_fields['option_name'],
				array( $this, 'sanitize_callback' )
			);
		}

		/**
		 * Create admin menu
		 */
		public function admin_menu() {
			$page_title  = $this->menu_fields['page_title'];
			$menu_title  = $this->menu_fields['menu_title'];
			$menu_slug   = $this->menu_fields['menu_slug'];
			$capability  = isset( $this->menu_fields['capability'] ) ? $this->menu_fields['capability'] : 'manage_options';
			$parent_slug = isset( $this->menu_fields['parent_slug'] ) ? $this->menu_fields['parent_slug'] : null;

			if ( ! empty( $parent_slug ) ) {
				add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug,
					array( $this, 'page_content' ) );
			} else {
				add_menu_page( $page_title, $menu_title, $capability, $menu_slug, array( $this, 'page_content' ) );
			}
		}

		/**
		 * Load page content
		 */
		public function page_content() {
			ob_start(); ?>

            <div class="wrap">
				<?php $this->option_page_tabs(); ?>
                <form autocomplete="off" method="POST" action="options.php">
					<?php
					// Output nonce, action, and option_page fields for a settings page.
					settings_fields( $this->menu_fields['option_name'] );
					// Get setting fields
					$this->setting_fields();
					// Echoes a submit button
					submit_button();
					?>
                </form>
            </div>
			<?php
			echo ob_get_clean();
		}

		/**
		 * Generate Option Page Tabs
		 *
		 * @return void
		 */
		private function option_page_tabs() {
			if ( count( $this->panels ) < 1 ) {
				return;
			}

			$current_tab = isset ( $_GET['tab'] ) ? $_GET['tab'] : $this->panels[0]['id'];
			$page        = $this->menu_fields['menu_slug'];

			echo '<h2 class="nav-tab-wrapper wp-clearfix">';
			foreach ( $this->panels as $tab ) {
				$class = ( $tab['id'] === $current_tab ) ? 'nav-tab nav-tab-active' : 'nav-tab';
				echo sprintf( '<a class="%s" href="?page=%s&tab=%s">%s</a>', $class, $page, $tab['id'], $tab['title'] );
			}
			echo '</h2>';
		}

		/**
		 * Sanitize each setting field as needed
		 *
		 * @param array $input Contains all settings fields as array keys
		 *
		 * @return array
		 */
		public function sanitize_callback( array $input ) {
			$output_array = array();
			$fields       = $this->fields;
			$options      = (array) get_option( $this->menu_fields['option_name'] );

			if ( empty( $options ) ) {
				$options = (array) $this->get_options();
			}

			if ( count( $this->panels ) > 0 ) {
				parse_str( $_POST['_wp_http_referer'], $referrer );
				$tab    = isset( $referrer['tab'] ) ? $referrer['tab'] : $this->panels[0]['id'];
				$fields = $this->getFieldsByPanel( $tab );
			}

			// Loop through each setting being saved and
			// pass it through a sanitization filter
			foreach ( $input as $key => $value ) {
				foreach ( $fields as $field ) {
					if ( $field['id'] == $key ) {
						$rule                 = empty( $field['validate'] ) ? $field['type'] : $field['validate'];
						$output_array[ $key ] = $this->validate( $value, $rule );
					}
				}
			}

			return array_filter( array_merge( $options, $output_array ) );
		}

		/**
		 * Get options parsed with default value
		 * @return array
		 */
		public function get_options() {
			$defaults = array();

			foreach ( $this->fields as $value ) {
				$std_value                = ( isset( $value['std'] ) ) ? $value['std'] : '';
				$defaults[ $value['id'] ] = $std_value;
			}

			$options = wp_parse_args( get_option( $this->menu_fields['option_name'] ), $defaults );

			return $this->options = $options;
		}

		/**
		 * Validate the option's value
		 *
		 * @param  mixed $input
		 * @param  string $validation_rule
		 *
		 * @return mixed
		 */
		private function validate( $input, $validation_rule = 'text' ) {
			switch ( $validation_rule ) {
				case 'text':
					return sanitize_text_field( $input );
					break;

				case 'number':
					return is_numeric( $input ) ? intval( $input ) : intval( $input );
					break;

				case 'url':
					return esc_url_raw( trim( $input ) );
					break;

				case 'email':
					return sanitize_email( $input );
					break;

				case 'checkbox':
					return in_array( $input, array( 'on', 'yes', '1', 1, 'true', true ) ) ? 1 : 0;
					break;

				case 'multi_checkbox':
					return $input;
					break;

				case 'radio':
					return sanitize_text_field( $input );
					break;

				case 'select':
					return sanitize_text_field( $input );
					break;

				case 'date':
					return date( 'F d, Y', strtotime( $input ) );
					break;

				case 'textarea':
					return wp_filter_nohtml_kses( $input );
					break;

				case 'inlinehtml':
					return wp_filter_kses( force_balance_tags( $input ) );
					break;

				case 'linebreaks':
					return wp_strip_all_tags( $input );
					break;

				case 'wp_editor':
					return wp_kses_post( $input );
					break;

				default:
					return sanitize_text_field( $input );
					break;
			}
		}

		/**
		 * Settings fields
		 *
		 *
		 * @return void
		 */
		private function setting_fields() {
			$table = "";

			$current_tab = isset ( $_GET['tab'] ) ? $_GET['tab'] : $this->panels[0]['id'];
			$panel       = $current_tab;
			$sections    = $this->getSections( $panel );

			foreach ( $sections as $section ) {
				if ( ! empty( $section['title'] ) ) {
					$table .= '<h2 class="title">' . esc_html( $section['title'] ) . '</h2>';
				}
				if ( ! empty( $section['description'] ) ) {
					$table .= '<p class="description">' . ( $section['description'] ) . '</p>';
				}

				$fields = $this->getFields( $section['id'] );

				$table .= "<table class='form-table'>";

				foreach ( $fields as $field ) {
					$name  = sprintf( '%s[%s]', $this->menu_fields['option_name'], $field['id'] );
					$type  = isset( $field['type'] ) ? $field['type'] : 'text';
					$value = isset( $this->options[ $field['id'] ] ) ? $this->options[ $field['id'] ] : '';

					$table .= "<tr>";
					$table .= sprintf( '<th scope="row"><label for="%1$s">%2$s</label></th>', $field['id'], $field['name'] );

					$table .= "<td>";

					if ( method_exists( $this, $type ) ) {
						$table .= $this->$type( $field, $name, $value );
					} else {
						$table .= $this->text( $field, $name, $value );
					}

					if ( ! empty( $field['desc'] ) ) {
						$table .= sprintf( '<p class="description">%s</p>', $field['desc'] );
					}
					$table .= "</td>";
					$table .= "</tr>";
				}

				$table .= "</table>";

			}
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
		protected function text( $field, $name, $value ) {
			$valid_types = array( 'text', 'email', 'password', 'number', 'url' );
			$type        = isset( $field['type'] ) && in_array( $field['type'], $valid_types ) ? esc_attr( $field['type'] ) : 'text';

			return '<input type="' . $type . '" class="regular-text" value="' . $value . '" id="' . $field['id'] . '" name="' . $name . '">';
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
		protected function color( $field, $name, $value ) {
			$default_color = ( isset( $field['std'] ) ) ? $field['std'] : "";

			return sprintf( '<input type="text" class="color-picker" value="%1$s" id="%2$s" name="%3$s" data-default-color="%4$s">', $value, $field['id'], $name, $default_color );
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
		protected function date( $field, $name, $value ) {
			if ( $this->is_date( $value ) ) {
				$value = date( "F d, Y", strtotime( $value ) );
			} else {
				$value = '';
			}

			return sprintf( '<input type="text" class="regular-text date-picker" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name );
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
		protected function textarea( $field, $name, $value ) {
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
		protected function checkbox( $field, $name, $value ) {
			$checked = in_array( $value, array( 'on', 'yes', '1', 1, 'true', true ) ) ? 'checked="checked"' : '';
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
		protected function multi_checkbox( $field, $name, $value ) {
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
		protected function radio( $field, $name, $value ) {
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
		protected function select( $field, $name, $value ) {
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
		protected function wp_editor( $field, $name, $value ) {
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
		 * Check if the given input is a valid date.
		 *
		 * @param  mixed $value
		 *
		 * @return boolean
		 */
		private function is_date( $value ) {
			if ( $value instanceof \DateTime ) {
				return true;
			}

			if ( strtotime( $value ) === false ) {
				return false;
			}

			$date = date_parse( $value );

			return checkdate( $date['month'], $date['day'], $date['year'] );
		}
	}
}
