<?php

$strings = 'tinyMCE.addI18n({' . _WP_Editors::$mce_locale . ':{
    shapla:{
        insert: "' . esc_js( __( 'Insert Shapla Shortcode', 'shapla' ) ) . '",
        alert: "' . esc_js( __( 'Alerts / Notification', 'shapla' ) ) . '",
        button: "' . esc_js( __( 'Buttons', 'shapla' ) ) . '",
        columns: "' . esc_js( __( 'Columns', 'shapla' ) ) . '",
        divider: "' . esc_js( __( 'Divider / Horizontal Ruler', 'shapla' ) ) . '",
        intro: "' . esc_js( __( 'Intro Text', 'shapla' ) ) . '",
        tabs: "' . esc_js( __( 'Tabs', 'shapla' ) ) . '",
        toggle: "' . esc_js( __( 'Toggle', 'shapla' ) ) . '",
        dropcap: "' . esc_js( __( 'Dropcap', 'shapla' ) ) . '",
        icon: "' . esc_js( __( 'Font Icon', 'shapla' ) ) . '",
        
        media_elements: "' . esc_js( __( 'Media Elements', 'shapla' ) ) . '",
        widget_area: "' . esc_js( __( 'Widget Area', 'shapla' ) ) . '",
        image: "' . esc_js( __( 'Image', 'shapla' ) ) . '",
        video: "' . esc_js( __( 'Video', 'shapla' ) ) . '",
        map: "' . esc_js( __( 'Google Map', 'shapla' ) ) . '",
    }
}})';
