<?php
$option_page = new ShaplaTools_Settings_API;

// Add settings page
$option_page->add_menu( [
	'parent_slug' => 'options-general.php',
	'page_title'  => __( 'ShaplaTools Settings', 'shaplatools' ),
	'menu_title'  => __( 'ShaplaTools', 'shaplatools' ),
	'capability'  => 'manage_options',
	'menu_slug'   => 'shaplatools',
	'option_name' => 'shaplatools_options',
] );

// Add settings page tab
$option_page->add_panel( [
	'id'    => 'general',
	'title' => __( 'General', 'shaplatools' ),
] );
$option_page->add_panel( [
	'id'    => 'social_link',
	'title' => __( 'Social Link', 'shaplatools' ),
] );

$option_page->add_section( [
	'id'          => 'section_twitter_settings',
	'title'       => __( 'Twitter Settings', 'shaplatools' ),
	'description' => sprintf(
		__( 'Consumer Key, Consumer Secret, Access Token and Access Token Secret is required for Twitter Feed Widget to work. Here is how you can %1$s get a key %2$s.', 'shaplatools' ),
		'<a target="_blank" href="https://apps.twitter.com/">',
		'</a>'
	),
	'panel'       => 'general',
	'priority'    => 10,
] );

$option_page->add_section( [
	'id'          => 'section_google_map',
	'title'       => __( 'Google Maps Settings', 'shaplatools' ),
	'description' => sprintf(
		__( 'An API key is required for Google Maps shortcode to work. Here is how you can %1$s get a key %2$s.', 'shaplatools' ),
		'<a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">',
		'</a>'
	),
	'panel'       => 'general',
	'priority'    => 20,
] );

$option_page->add_section( [
	'id'          => 'section_facebook',
	'title'       => __( 'Facebook Settings', 'shaplatools' ),
	'description' => sprintf(
		__( 'An App ID is required for Facebook Like Box widget to work. Here is how you can %1$s get a ID %2$s.', 'shaplatools' ),
		'<a target="_blank" href="https://developers.facebook.com/">',
		'</a>'
	),
	'panel'       => 'general',
	'priority'    => 30,
] );

$option_page->add_section( [
	'id'          => 'section_social_link',
	'title'       => __( 'Social Link Settings', 'shaplatools' ),
	'description' => '',
	'panel'       => 'social_link',
] );

// Add general settings page fields
$option_page->add_field( [
	'id'      => 'google_map_api_key',
	'type'    => 'text',
	'name'    => __( 'API key', 'shaplatools' ),
	'desc'    => __( 'Enter Google Map API Key', 'shaplatools' ),
	'std'     => '',
	'section' => 'section_google_map'
] );
$option_page->add_field( [
	'id'      => 'facebook_app_id',
	'type'    => 'text',
	'name'    => __( 'App ID', 'shaplatools' ),
	'desc'    => __( 'Enter Facebook App ID', 'shaplatools' ),
	'std'     => '',
	'section' => 'section_facebook'
] );

$option_page->add_field( [
	'id'      => 'twitter_consumer_key',
	'type'    => 'text',
	'name'    => __( 'OAuth Consumer Key', 'shaplatools' ),
	'desc'    => __( 'Enter twitter OAuth Consumer Key', 'shaplatools' ),
	'std'     => '',
	'section' => 'section_twitter_settings'
] );
$option_page->add_field( [
	'id'      => 'twitter_consumer_secret',
	'type'    => 'text',
	'name'    => __( 'OAuth Consumer Secret', 'shaplatools' ),
	'desc'    => __( 'Enter twitter OAuth Consumer Secret', 'shaplatools' ),
	'std'     => '',
	'section' => 'section_twitter_settings'
] );
$option_page->add_field( [
	'id'      => 'twitter_access_key',
	'type'    => 'text',
	'name'    => __( 'OAuth Access Token', 'shaplatools' ),
	'desc'    => __( 'Enter twitter OAuth Access Token', 'shaplatools' ),
	'std'     => '',
	'section' => 'section_twitter_settings'
] );
$option_page->add_field( [
	'id'      => 'twitter_access_secret',
	'type'    => 'text',
	'name'    => __( 'OAuth Access Secret', 'shaplatools' ),
	'desc'    => __( 'Enter twitter OAuth Access Secret', 'shaplatools' ),
	'std'     => '',
	'section' => 'section_twitter_settings'
] );

// Add social link settings page fields
$option_page->add_field( [
	'id'      => 'android',
	'type'    => 'url',
	'name'    => __( 'Android', 'shaplatools' ),
	'section' => 'section_social_link'
] );
$option_page->add_field( [
	'id'      => 'apple',
	'type'    => 'url',
	'name'    => __( 'Apple', 'shaplatools' ),
	'section' => 'section_social_link'
] );
$option_page->add_field( [
	'id'      => 'behance',
	'type'    => 'url',
	'name'    => __( 'Behance', 'shaplatools' ),
	'desc'    => 'e.g. https://www.behance.net/username',
	'section' => 'section_social_link'
] );
$option_page->add_field( [
	'id'      => 'bitbucket',
	'type'    => 'url',
	'name'    => __( 'Bitbucket', 'shaplatools' ),
	'desc'    => 'e.g. https://bitbucket.org/username',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'codepen',
	'name'    => __( 'CodePen', 'shaplatools' ),
	'desc'    => 'e.g. http://codepen.io/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'deviantart',
	'name'    => __( 'Deviant Art', 'shaplatools' ),
	'desc'    => 'e.g. http://username.deviantart.com',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'dribbble',
	'name'    => __( 'Dribbble', 'shaplatools' ),
	'desc'    => 'e.g. http://dribbble.com/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'dropbox',
	'name'    => __( 'Dropbox', 'shaplatools' ),
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'facebook',
	'name'    => __( 'Facebook', 'shaplatools' ),
	'desc'    => 'e.g. http://www.facebook.com/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'flickr',
	'name'    => __( 'Flickr', 'shaplatools' ),
	'desc'    => 'e.g. http://www.flickr.com/photos/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'foursquare',
	'name'    => __( 'Foursquare', 'shaplatools' ),
	'desc'    => 'e.g. https://foursquare.com/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'github',
	'name'    => __( 'GitHub', 'shaplatools' ),
	'desc'    => 'e.g. https://github.com/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'google-plus',
	'name'    => __( 'Google+', 'shaplatools' ),
	'desc'    => 'e.g. https://plus.google.com/userID',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'instagram',
	'name'    => __( 'Instagram', 'shaplatools' ),
	'desc'    => 'e.g. http://instagram.com/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'linkedin',
	'name'    => __( 'LinkedIn', 'shaplatools' ),
	'desc'    => 'e.g. http://www.linkedin.com/in/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'mail',
	'name'    => __( 'Mail', 'shaplatools' ),
	'desc'    => 'e.g. mailto:user@name.com',
	'type'    => 'email',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'pinterest',
	'name'    => __( 'Pinterest', 'shaplatools' ),
	'desc'    => 'e.g. http://pinterest.com/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'rss',
	'name'    => __( 'RSS', 'shaplatools' ),
	'desc'    => 'e.g. http://example.com/feed',
	'type'    => 'url',
	'section' => 'section_social_link',
] );

$option_page->add_field( [
	'id'      => 'skype',
	'name'    => __( 'Skype', 'shaplatools' ),
	'type'    => 'text',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'stack-exchange',
	'name'    => __( 'Stack Exchange', 'shaplatools' ),
	'desc'    => 'http://stackexchange.com/users/userID',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'stack-overflow',
	'name'    => __( 'Stack Overflow', 'shaplatools' ),
	'desc'    => 'e.g. http://stackoverflow.com/users/userID',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'trello',
	'name'    => __( 'Trello', 'shaplatools' ),
	'desc'    => 'e.g. https://trello.com/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'tumblr',
	'name'    => __( 'Tumblr', 'shaplatools' ),
	'desc'    => 'e.g. http://username.tumblr.com',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'twitter',
	'name'    => __( 'Twitter', 'shaplatools' ),
	'desc'    => 'e.g. http://twitter.com/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'vimeo',
	'name'    => __( 'Vimeo', 'shaplatools' ),
	'desc'    => 'e.g. https://vimeo.com/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'vine',
	'name'    => __( 'Vine', 'shaplatools' ),
	'desc'    => 'e.g. https://vine.co/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'windows',
	'name'    => __( 'Windows', 'shaplatools' ),
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'wordpress',
	'name'    => __( 'WordPress', 'shaplatools' ),
	'desc'    => 'e.g. https://profiles.wordpress.org/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$option_page->add_field( [
	'id'      => 'xing',
	'name'    => __( 'Xing', 'shaplatools' ),
	'type'    => 'url',
	'section' => 'section_social_link'
] );
$option_page->add_field( [
	'id'      => 'youtube',
	'name'    => __( 'YouTube', 'shaplatools' ),
	'desc'    => 'e.g. http://www.youtube.com/user/username',
	'type'    => 'url',
	'section' => 'section_social_link'
] );

$this->options                 = $option_page->get_options();
$GLOBALS['shapla_social_link'] = $option_page->getFieldsByPanel( 'social_link' );
