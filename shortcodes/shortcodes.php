<?php
/**
 * All Plugin Shortcodes
 *
 * @package ShaplaTools
 */


/**
 * Columns
 */
if( ! function_exists('shapla_one_third' ) ) :
function shapla_one_third( $atts, $content = null ) {
	return '<div class="shapla-column shapla-one-third">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode('shapla_one_third', 'shapla_one_third');

if( ! function_exists('shapla_one_third_last' ) ) :
function shapla_one_third_last( $atts, $content = null ) {
	return '<div class="shapla-column shapla-one-third shapla-column-last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
endif;
add_shortcode('shapla_one_third_last', 'shapla_one_third_last');

if( ! function_exists( 'shapla_two_third' ) ) :
function shapla_two_third( $atts, $content = null) {
	return '<div class="shapla-column shapla-two-third">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode( 'shapla_two_third', 'shapla_two_third' );

if( ! function_exists( 'shapla_two_third_last' ) ) :
function shapla_two_third_last( $atts, $content = null) {
	return '<div class="shapla-column shapla-two-third shapla-column-last">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode( 'shapla_two_third_last', 'shapla_two_third_last' );

if (!function_exists( 'shapla_one_half' ) ) :
function shapla_one_half( $atts, $content = null ) {
	return '<div class="shapla-column shapla-one-half">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode( 'shapla_one_half', 'shapla_one_half' );

if ( ! function_exists( 'shapla_one_half_last' ) ) :
function shapla_one_half_last( $atts, $content = null ) {
	return '<div class="shapla-column shapla-one-half shapla-column-last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'shapla_one_half_last', 'shapla_one_half_last' );

if ( ! function_exists( 'shapla_one_fourth' ) ) :
function shapla_one_fourth( $atts, $content = null ) {
	return '<div class="shapla-column shapla-one-fourth">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode( 'shapla_one_fourth', 'shapla_one_fourth' );

if ( ! function_exists('shapla_one_fourth_last' ) ) :
function shapla_one_fourth_last( $atts, $content = null ) {
	return '<div class="shapla-column shapla-one-fourth shapla-column-last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'shapla_one_fourth_last', 'shapla_one_fourth_last' );

if ( ! function_exists('shapla_three_fourth' ) ) :
function shapla_three_fourth( $atts, $content = null ) {
	return '<div class="shapla-column shapla-three-fourth">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode( 'shapla_three_fourth', 'shapla_three_fourth' );

if ( ! function_exists('shapla_three_fourth_last' ) ) :
function shapla_three_fourth_last( $atts, $content = null ) {
	return '<div class="shapla-column shapla-three-fourth shapla-column-last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'shapla_three_fourth_last', 'shapla_three_fourth_last' );

if ( ! function_exists('shapla_one_fifth' ) ) :
function shapla_one_fifth( $atts, $content = null ) {
	return '<div class="shapla-column shapla-one-fifth">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode( 'shapla_one_fifth', 'shapla_one_fifth' );

if ( ! function_exists('shapla_one_fifth_last' ) ) :
function shapla_one_fifth_last( $atts, $content = null ) {
	return '<div class="shapla-column shapla-one-fifth shapla-column-last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
add_shortcode( 'shapla_one_fifth_last', 'shapla_one_fifth_last' );
endif;

if ( ! function_exists('shapla_two_fifth' ) ) :
function shapla_two_fifth( $atts, $content = null ) {
	return '<div class="shapla-column shapla-two-fifth">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode( 'shapla_two_fifth', 'shapla_two_fifth' );

if ( ! function_exists('shapla_two_fifth_last' ) ) :
function shapla_two_fifth_last( $atts, $content = null ) {
	return '<div class="shapla-column shapla-two-fifth shapla-column-last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'shapla_two_fifth_last', 'shapla_two_fifth_last' );

if ( ! function_exists('shapla_three_fifth' ) ) :
function shapla_three_fifth( $atts, $content = null ) {
	return '<div class="shapla-column shapla-three-fifth">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode( 'shapla_three_fifth', 'shapla_three_fifth' );

if ( ! function_exists('shapla_three_fifth_last' ) ) :
function shapla_three_fifth_last( $atts, $content = null ) {
	return '<div class="shapla-column shapla-three-fifth shapla-column-last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'shapla_three_fifth_last', 'shapla_three_fifth_last' );

if ( ! function_exists('shapla_four_fifth' ) ) :
function shapla_four_fifth( $atts, $content = null ) {
	return '<div class="shapla-column shapla-four-fifth">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode( 'shapla_four_fifth', 'shapla_four_fifth' );

if ( ! function_exists( 'shapla_four_fifth_last' ) ) :
function shapla_four_fifth_last( $atts, $content = null ) {
	return '<div class="shapla-column shapla-four-fifth shapla-column-last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'shapla_four_fifth_last', 'shapla_four_fifth_last' );

if ( ! function_exists( 'shapla_one_sixth' ) ) :
function shapla_one_sixth( $atts, $content = null ) {
	return '<div class="shapla-column shapla-one-sixth">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode( 'shapla_one_sixth', 'shapla_one_sixth' );

if ( ! function_exists( 'shapla_one_sixth_last' ) ) :
function shapla_one_sixth_last( $atts, $content = null ) {
	return '<div class="shapla-column shapla-one-sixth shapla-column-last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'shapla_one_sixth_last', 'shapla_one_sixth_last' );

if ( ! function_exists( 'shapla_five_sixth' ) ) :
function shapla_five_sixth( $atts, $content = null ) {
	return '<div class="shapla-column shapla-five-sixth">' . do_shortcode($content) . '</div>';
}
endif;
add_shortcode( 'shapla_five_sixth', 'shapla_five_sixth' );

if ( ! function_exists( 'shapla_five_sixth_last' ) ) :
function shapla_five_sixth_last( $atts, $content = null ) {
	return '<div class="shapla-column shapla-five-sixth shapla-column-last">' . do_shortcode($content) . '</div><div class="clear"></div>';
}
endif;
add_shortcode( 'shapla_five_sixth_last', 'shapla_five_sixth_last' );


if( ! function_exists( 'shapla_button' ) ) :
/**
 * Buttons
 */
function shapla_button( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'        => '#',
		'target'     => '_self',
		'style'      => 'grey',
		'size'       => 'small',
		'type'       => 'round',
		'icon'       => '',
		'icon_order' => 'before'
	), $atts ) );

	$button_icon = '';
	$class       = " shapla-button--{$size}";
	$class       .= " shapla-button--{$style}";
	$class       .= " shapla-button--{$type}";

	if( ! empty($icon) ) {
		if ( $icon_order == 'before' ) {
			$button_content = shapla_icon( array( 'icon' => $icon ) );
			$button_content .= do_shortcode($content);
		} else {
			$button_content = do_shortcode($content);
			$button_content .= shapla_icon( array( 'icon' => $icon ) );
		}
		$class .= " shapla-icon--{$icon_order}";
	} else {
		$button_content = do_shortcode($content);
	}

	return '<a target="'.$target.'" href="'.$url.'" class="shapla-button'. $class .'">'. $button_content .'</a>';
}
endif;

add_shortcode( 'shapla_button', 'shapla_button' );


if( ! function_exists( 'shapla_alert') ) :
/**
 * Alerts
 */
function shapla_alert( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'style' => 'white'
    ), $atts));
	return '<div class="shapla-section shapla-alert shapla-alert--'.$style.'">' . do_shortcode($content) . '</div>';
}
endif;

add_shortcode( 'shapla_alert', 'shapla_alert' );


if( ! function_exists( 'shapla_divider' ) ) :
function shapla_divider( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'style' => 'plain'
	), $atts ) );
	return '<hr class="shapla-section shapla-divider shapla-divider--'.$style.'">';
}
endif;

add_shortcode( 'shapla_divider', 'shapla_divider' );


if( ! function_exists( 'shapla_intro' ) ):
function shapla_intro( $atts, $content = null ) {
	return '<section class="shapla-section shapla-intro-text">' . wpautop( do_shortcode( $content ) ) . '</section>';
}
endif;

add_shortcode( 'shapla_intro', 'shapla_intro' );


if( ! function_exists( 'shapla_tabs' ) ) :
/**
 * Shortcode for tabs.
 *
 * @return void
 */
function shapla_tabs( $atts, $content = null ) {
	$defaults = array(
		'style' => 'normal'
	);
	extract( shortcode_atts( $defaults, $atts ) );

	preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );

	$tab_titles = array();
    if( isset($matches[1]) ){ $tab_titles = $matches[1]; }

    $output = '';

    if( count( $tab_titles ) ) {
    	$output .= '<section id="shapla-tabs-'. rand(1, 100) .'" class="shapla-section shapla-tabs shapla-tabs--'. $style .'"><div class="shapla-tab-inner">';
    	$output .= '<ul class="shapla-nav shapla-clearfix">';

    	foreach( $tab_titles as $tab ) {
    		$output .= '<li><a href="#shapla-tab-'. sanitize_title( $tab[0] ) .'">' . $tab[0] . '</a></li>';
    	}

    	$output .= '</ul>';
    	$output .= do_shortcode( $content );
    	$output .= '</div></section>';
    } else {
    	$output .= do_shortcode( $content );
    }
    return $output;
}
endif;

add_shortcode( 'shapla_tabs', 'shapla_tabs' );


if( ! function_exists( 'shapla_tab' ) ) :
function shapla_tab( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title' => __( 'Tab', 'shapla' )
	), $atts ) );
	return '<div id="shapla-tab-'. sanitize_title( $title ) .'" class="shapla-tab">'. do_shortcode( $content ) .'</div>';
}
endif;

add_shortcode( 'shapla_tab', 'shapla_tab' );


if( ! function_exists( 'shapla_toggle' ) ) :
function shapla_toggle( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title' => __( 'Title Goes Here', 'shapla' ),
		'state' => 'open',
		'style' => 'normal'
	), $atts ) );
	return "<div data-id='".$state."' class=\"shapla-section shapla-toggle shapla-toggle--". $style ."\"><span class=\"shapla-toggle-title\">". $title ."</span><div class=\"shapla-toggle-inner\"><div class=\"shapla-toggle-content\">". do_shortcode($content) ."</div></div></div>";
}
endif;

add_shortcode( 'shapla_toggle', 'shapla_toggle' );

if( ! function_exists( 'shapla_dropcap' ) ) :
function shapla_dropcap( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'style'     => 'normal',
		'font_size' => '50px'
	), $atts ) );

	return "<span class=\"shapla-dropcap shapla-dropcap--$style\" style=\"font-size: $font_size; line-height: $font_size; width: $font_size; height: $font_size;\">". do_shortcode( $content ) ."</span>";
}
endif;

add_shortcode( 'shapla_dropcap', 'shapla_dropcap' );

if( ! function_exists( 'shapla_image' ) ) :
function shapla_image( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'style'     => 'grayscale',
		'alignment' => 'none',
		'src'       => '',
		'url'       => ''
	), $atts ) );

	$output = "<figure class=\"shapla-section shapla-image shapla-image--$style shapla-image--$alignment\" >";

	if($url != ''){
		$output .= "<a href=\"". esc_url($url) ."\"><img src=\"$src\" alt=\"\"></a>";
	}else{
		$output .= "<img src=\"". esc_url($src) ."\" alt=\"\">";
	}

	$output .= "</figure>";

	return $output;
}
endif;

add_shortcode( 'shapla_image', 'shapla_image' );

if( ! function_exists( 'shapla_video' ) ) :
function shapla_video( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'src' => ''
	), $atts ) );

	return "<div class=\"shapla-section shapla-video\" >". $GLOBALS['wp_embed']->run_shortcode( '[embed]'. esc_url( $src ) .'[/embed]' ) ."</div>";
}
endif;

add_shortcode( 'shapla_video', 'shapla_video' );

if( ! function_exists( 'shapla_icon') ) :
/**
 * FontAwesome Icon shortcode.
 */
function shapla_icon( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'icon'       => '',
		'url'        => '',
		'size'       => '',
		'new_window' => 'no'
	), $atts ) );

	$new_window = ( $new_window == "no") ? '_self' : '_blank';

	$size = esc_attr( $size );

	$output = '';
	$attrs  = '';

	if( ! empty($url) ){
		$a_attrs = ' href="'. esc_url($url) .'" target="'. esc_attr($new_window) .'"';
	}

	if( !empty($size) ) {
		$attrs .= ' style="font-size:'. $size .';line-height:'. $size .'"';
	}

	if( $url != '' ){
		$output .= '<a class="shapla-icon-link" '. $a_attrs .'><i class="fa fa-'. $icon .'" style="font-size: '. $size .'; line-height: '. $size .';"></i></a>';
	}else{
		$output .= '<i class="fa fa-'. esc_attr($icon) .'" '. $attrs .'></i>';
	}

	return $output;
}
endif;

add_shortcode( 'shapla_icon', 'shapla_icon' );

if( ! function_exists( 'shapla_map') ) :
/**
 * Google Map Shortcode
 *
 * @since 1.0.4
 */
function shapla_map( $atts ) {
	extract( shortcode_atts( array(
		'lat'    => '37.42200',
		'long'   => '-122.08395',
		'width'  => '100%',
		'height' => '350px',
		'zoom'   => 15,
		'style'  => 'none'
	), $atts ) );

	$map_styles = array(
		'none'             => '[]',
		'mixed'            => '[{"featureType":"landscape","stylers":[{"hue":"#00dd00"}]},{"featureType":"road","stylers":[{"hue":"#dd0000"}]},{"featureType":"water","stylers":[{"hue":"#000040"}]},{"featureType":"poi.park","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","stylers":[{"hue":"#ffff00"}]},{"featureType":"road.local","stylers":[{"visibility":"off"}]}]',
		'pale_dawn'        => '[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]',
		'greyscale'        => '[{"featureType":"all","stylers":[{"saturation":-100},{"gamma":0.5}]}]',
		'bright_bubbly'    => '[{"featureType":"water","stylers":[{"color":"#19a0d8"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"weight":6}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#e85113"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-40}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-20}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"road.highway","elementType":"labels.icon"},{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape","stylers":[{"lightness":20},{"color":"#efe9e4"}]},{"featureType":"landscape.man_made","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"hue":"#11ff00"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"hue":"#4cff00"},{"saturation":58}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#f0e4d3"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-10}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"simplified"}]}]',
		'subtle_grayscale' => '[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]'
	);

	$map_id = 'map'. rand(0, 99);

	wp_enqueue_script( 'google-maps', ( is_ssl() ? 'https' : 'http' ) . '://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false' );

	?>

	<script type="text/javascript">
	    jQuery(window).load(function(){
    	    var options = {
    	    	id: "<?php echo $map_id; ?>",
    	    	styles: <?php echo $map_styles[$style]; ?>,
    	    	zoom: <?php echo $zoom; ?>,
    	    	center: {
    	    		lat: "<?php echo $lat; ?>",
    	    		long: "<?php echo $long; ?>"
    	    	}
    	    };
    	    Shaplatools.Map.init(options);
	    });
	</script>

	<?php

	return "<section id='{$map_id}' class='shapla-section google-map' style='width:{$width};height:{$height};'></section>";
}
endif;

add_shortcode( 'shapla_map', 'shapla_map' );

if ( ! function_exists( 'shapla_social' ) ) :
/**
 * Social shortcode.
 *
 * Display links to social profiles.
 *
 * @since 1.2
 */
function shapla_social( $atts ) {
	extract( shortcode_atts( array(
		'id'    => 'all',
		'style' => 'normal'
	), $atts ) );

	$registered_settings = shaplatools_get_registered_settings();
	$social_urls         = array_keys($registered_settings['social']);
	$settings            = get_option('shaplatools_options');

	$output              = '<div class="shapla-social-icons '. $style .'">';

	if ( $id == '' || $id == "all" ) {
		$id = $social_urls;
	} else {
		$id = explode(',', $id);
	}

	foreach( $id as $slug ) {
		$slug = trim($slug);
		if( isset( $settings[$slug] ) && $settings[$slug] != '' ) {
			$class = $slug;

			if( 'mail'  == $slug ) $class = 'envelope';
			if( 'vimeo' == $slug ) $class = 'vimeo-square';

			$output .= "<a href='". esc_url( $settings[$slug] ) ."' target='_blank'><i class='fa fa-{$class}'></i></a>";
		}
	}
	$output .= "</div>";

	return $output;

}
endif;
add_shortcode( 'shapla_social', 'shapla_social' );

if ( ! function_exists( 'shapla_columns' ) ) :
/**
 * Shapla Columns shortcodes.
 *
 * Wrapper for shapla_column shortcodes.
 *
 * @since 1.2.4
 *
 * @param  array $atts Shortcode attributes.
 * @param  string $content Shortcode content.
 *
 * @return mixed
 */
function shapla_columns( $atts, $content = null ) {
    return "<section class='shapla-section shapla-columns'>". do_shortcode( $content ) ."</section>";
}
endif;
add_shortcode( 'shapla_columns', 'shapla_columns' );
