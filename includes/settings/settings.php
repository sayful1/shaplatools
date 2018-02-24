<?php
$shaplatools_settings_page = new ShaplaTools_Settings_API;

// Add settings page
$shaplatools_settings_page->add_menu( [
	'parent_slug' => 'options-general.php',
	'page_title'  => __( 'ShaplaTools Settings', 'shaplatools' ),
	'menu_title'  => __( 'ShaplaTools', 'shaplatools' ),
	'capability'  => 'manage_options',
	'menu_slug'   => 'shaplatools',
	'option_name' => 'shaplatools_options',
] );

// Add settings page tab
$shaplatools_settings_page->add_tab( [
	'id'    => 'general',
	'title' => __( 'General', 'shaplatools' ),
] );
$shaplatools_settings_page->add_tab( [
	'id'    => 'social_link',
	'title' => __( 'Social Link', 'shaplatools' ),
] );

// Add general settings page fields
$shaplatools_settings_page->add_field( [
	'id'   => 'google_map_api_key',
	'type' => 'text',
	'name' => __( 'Google Maps API key', 'shaplatools' ),
	'desc' => sprintf(
		__( 'An API key is required for Google Maps shortcode to work. Here is how you can %1$s get a key %2$s.', 'shaplatools' ),
		'<a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">',
		'</a>'
	),
	'std'  => '',
	'tab'  => 'general'
] );

// Add social link settings page fields
$shaplatools_settings_page->add_field( [
	'id'   => 'android',
	'type' => 'url',
	'name' => __( 'Android', 'shaplatools' ),
	'tab'  => 'social_link'
] );
$shaplatools_settings_page->add_field( [
	'id'   => 'apple',
	'type' => 'url',
	'name' => __( 'Apple', 'shaplatools' ),
	'tab'  => 'social_link'
] );
$shaplatools_settings_page->add_field( [
	'id'   => 'behance',
	'type' => 'url',
	'name' => __( 'Behance', 'shaplatools' ),
	'desc' => 'e.g. https://www.behance.net/username',
	'tab'  => 'social_link'
] );
$shaplatools_settings_page->add_field( [
	'id'   => 'bitbucket',
	'type' => 'url',
	'name' => __( 'Bitbucket', 'shaplatools' ),
	'desc' => 'e.g. https://bitbucket.org/username',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'codepen',
	'name' => __( 'CodePen', 'shaplatools' ),
	'desc' => 'e.g. http://codepen.io/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'deviantart',
	'name' => __( 'Deviant Art', 'shaplatools' ),
	'desc' => 'e.g. http://username.deviantart.com',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'dribbble',
	'name' => __( 'Dribbble', 'shaplatools' ),
	'desc' => 'e.g. http://dribbble.com/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'dropbox',
	'name' => __( 'Dropbox', 'shaplatools' ),
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'facebook',
	'name' => __( 'Facebook', 'shaplatools' ),
	'desc' => 'e.g. http://www.facebook.com/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'flickr',
	'name' => __( 'Flickr', 'shaplatools' ),
	'desc' => 'e.g. http://www.flickr.com/photos/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'foursquare',
	'name' => __( 'Foursquare', 'shaplatools' ),
	'desc' => 'e.g. https://foursquare.com/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'github',
	'name' => __( 'GitHub', 'shaplatools' ),
	'desc' => 'e.g. https://github.com/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'google-plus',
	'name' => __( 'Google+', 'shaplatools' ),
	'desc' => 'e.g. https://plus.google.com/userID',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'instagram',
	'name' => __( 'Instagram', 'shaplatools' ),
	'desc' => 'e.g. http://instagram.com/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'linkedin',
	'name' => __( 'LinkedIn', 'shaplatools' ),
	'desc' => 'e.g. http://www.linkedin.com/in/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'mail',
	'name' => __( 'Mail', 'shaplatools' ),
	'desc' => 'e.g. mailto:user@name.com',
	'type' => 'email',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'pinterest',
	'name' => __( 'Pinterest', 'shaplatools' ),
	'desc' => 'e.g. http://pinterest.com/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'rss',
	'name' => __( 'RSS', 'shaplatools' ),
	'desc' => 'e.g. http://example.com/feed',
	'type' => 'url',
	'tab'  => 'social_link',
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'skype',
	'name' => __( 'Skype', 'shaplatools' ),
	'type' => 'text',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'stack-exchange',
	'name' => __( 'Stack Exchange', 'shaplatools' ),
	'desc' => 'http://stackexchange.com/users/userID',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'stack-overflow',
	'name' => __( 'Stack Overflow', 'shaplatools' ),
	'desc' => 'e.g. http://stackoverflow.com/users/userID',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'trello',
	'name' => __( 'Trello', 'shaplatools' ),
	'desc' => 'e.g. https://trello.com/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'tumblr',
	'name' => __( 'Tumblr', 'shaplatools' ),
	'desc' => 'e.g. http://username.tumblr.com',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'twitter',
	'name' => __( 'Twitter', 'shaplatools' ),
	'desc' => 'e.g. http://twitter.com/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'vimeo',
	'name' => __( 'Vimeo', 'shaplatools' ),
	'desc' => 'e.g. https://vimeo.com/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'vine',
	'name' => __( 'Vine', 'shaplatools' ),
	'desc' => 'e.g. https://vine.co/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'windows',
	'name' => __( 'Windows', 'shaplatools' ),
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'wordpress',
	'name' => __( 'WordPress', 'shaplatools' ),
	'desc' => 'e.g. https://profiles.wordpress.org/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$shaplatools_settings_page->add_field( [
	'id'   => 'xing',
	'name' => __( 'Xing', 'shaplatools' ),
	'type' => 'url',
	'tab'  => 'social_link'
] );
$shaplatools_settings_page->add_field( [
	'id'   => 'youtube',
	'name' => __( 'YouTube', 'shaplatools' ),
	'desc' => 'e.g. http://www.youtube.com/user/username',
	'type' => 'url',
	'tab'  => 'social_link'
] );

$this->options                 = $shaplatools_settings_page->get_options();
$GLOBALS['shapla_social_link'] = $shaplatools_settings_page->filter_fields_by_tab( 'social_link' );
