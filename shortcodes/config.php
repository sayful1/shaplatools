<?php

global $shaplatools;

$shapla_shortcodes['button'] = array(
	'no_preview' => true,
	'params' => array(
		'url' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'Button URL', 'shapla' ),
			'desc'  => __( 'Add the button&lsquo;s url e.g. http://example.com', 'shapla' )
		),
		'style' => array(
			'type'    => 'select',
			'label'   => __( 'Button Style', 'shapla' ),
			'desc'    => __( 'Select the button&lsquo;s style, ie the button&lsquo;s colour', 'shapla' ),
			'std'     => 'black',
			'options' => array(
				'grey'       => __( 'Grey', 'shapla' ),
				'black'      => __( 'Black', 'shapla' ),
				'green'      => __( 'Green', 'shapla' ),
				'light-blue' => __( 'Light Blue', 'shapla' ),
				'blue'       => __( 'Blue', 'shapla' ),
				'red'        => __( 'Red', 'shapla' ),
				'orange'     => __( 'Orange', 'shapla' ),
				'purple'     => __( 'Purple', 'shapla' ),
				'white'      => __( 'White', 'shapla' )
			)
		),
		'size' => array(
			'type'    => 'select',
			'label'   => __( 'Button Size', 'shapla' ),
			'desc'    => __( 'Select the button&lsquo;s size', 'shapla' ),
			'std'     => 'small',
			'options' => array(
				'small'  => __( 'Small', 'shapla' ),
				'medium' => __( 'Medium', 'shapla' ),
				'large'  => __( 'Large', 'shapla' )
			)
		),
		'type' => array(
			'type'    => 'select',
			'label'   => __( 'Button Type', 'shapla' ),
			'desc'    => __( 'Select the button&lsquo;s type', 'shapla' ),
			'options' => array(
				'normal' => __( 'Normal', 'shapla' ),
				'stroke' => __( 'Stroke', 'shapla' )
			)
		),
		'icon' => array(
			'std'   => '',
			'type'  => 'icons',
			'label' => __( 'Button Icon', 'shapla' ),
			'desc'  => __( 'Choose an icon', 'shapla' )
		),
		'icon_order' => array(
			'type'    => 'select',
			'label'   => __( 'Font Order', 'shapla' ),
			'desc'    => __( 'Select if the icon should display before text or after text.', 'shapla' ),
			'std'     => 'before',
			'options' => array(
				'before' => __( 'Before Text', 'shapla' ),
				'after'  => __( 'After Text', 'shapla' )
			)
		),
		'target' => array(
			'type'    => 'select',
			'label'   => __( 'Button Target', 'shapla' ),
			'desc'    => __( '_self = open in same window. _blank = open in new window', 'shapla' ),
			'std'     => '_self',
			'options' => array(
				'_self'  => __( '_self', 'shapla' ),
				'_blank' => __( '_blank', 'shapla' )
			)
		),
		'content' => array(
			'std'   => 'Button Text',
			'type'  => 'text',
			'label' => __( 'Button&lsquo;s Text', 'shapla' ),
			'desc'  => __( 'Add the button&lsquo;s text', 'shapla' ),
		)
	),
	'shortcode'   => '[shapla_button url="{{url}}" style="{{style}}" size="{{size}}" type="{{type}}" target="{{target}}" icon="{{icon}}" icon_order="{{icon_order}}"]{{content}}[/shapla_button]',
	'popup_title' => __('Insert Button Shortcode', 'shapla')
);


$shapla_shortcodes['alert'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type'    => 'select',
			'label'   => __('Alert Style', 'shapla'),
			'desc'    => __('Select the alert&lsquo;s style, i.e. the alert colour', 'shapla'),
			'std'     => 'red',
			'options' => array(
				'white'  => __( 'White', 'shapla' ),
				'grey'   => __( 'Grey', 'shapla' ),
				'red'    => __( 'Red', 'shapla' ),
				'yellow' => __( 'Yellow', 'shapla' ),
				'green'  => __( 'Green', 'shapla' ),
				'blue'   => __( 'Blue', 'shapla' )
			)
		),
		'content' => array(
			'std'   => 'Your Alert!',
			'type'  => 'textarea',
			'label' => __( 'Alert Text', 'shapla' ),
			'desc'  => __( 'Add the alert&lsquo;s text', 'shapla' )
		)
	),
	'shortcode' => '[shapla_alert style="{{style}}"]{{content}}[/shapla_alert]',
	'popup_title' => __('Insert Alert Shortcode', 'shapla')
);

$shapla_shortcodes['toggle'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type'    => 'select',
			'label'   => __('Toggle Style', 'shapla'),
			'desc'    => __('Select the toggle&lsquo;s style', 'shapla'),
			'options' => array(
				'normal' => __( 'Normal', 'shapla' ),
				'stroke' => __( 'Stroke', 'shapla' ),
			)
		),
		'title' => array(
			'type'  => 'text',
			'label' => __('Toggle Content Title', 'shapla'),
			'desc'  => __('Add the title that will go above the toggle content', 'shapla'),
			'std'   => 'Title'
		),
		'content' => array(
			'std'   => 'Content',
			'type'  => 'textarea',
			'label' => __('Toggle Content', 'shapla'),
			'desc'  => __('Add the toggle content. Will accept HTML', 'shapla'),
		),
		'state' => array(
			'type'    => 'select',
			'label'   => __('Toggle State', 'shapla'),
			'desc'    => __('Select the state of the toggle on page load', 'shapla'),
			'options' => array(
				'open'   => __( 'Open', 'shapla' ),
				'closed' => __( 'Closed', 'shapla' )
			)
		),
	),
	'shortcode'   => '[shapla_toggle style="{{style}}" title="{{title}}" state="{{state}}"]{{content}}[/shapla_toggle]',
	'popup_title' => __('Insert Toggle Content Shortcode', 'shapla')
);

$shapla_shortcodes['columns'] = array(
	'params'      => array(),
	'shortcode'   => '[shapla_columns]{{child_shortcode}}[/shapla_columns]', // as there is no wrapper shortcode
	'popup_title' => __('Insert Columns Shortcode', 'shapla'),
	'no_preview'  => true,

	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'column' => array(
				'type'    => 'select',
				'label'   => __('Column Type', 'shapla'),
				'desc'    => __('Select the type, ie width of the column.', 'shapla'),
				'options' => array(
					'shapla_one_third'         => __( 'One Third', 'shapla' ),
					'shapla_one_third_last'    => __( 'One Third Last', 'shapla' ),
					'shapla_two_third'         => __( 'Two Thirds', 'shapla' ),
					'shapla_two_third_last'    => __( 'Two Thirds Last', 'shapla' ),
					'shapla_one_half'          => __( 'One Half', 'shapla' ),
					'shapla_one_half_last'     => __( 'One Half Last', 'shapla' ),
					'shapla_one_fourth'        => __( 'One Fourth', 'shapla' ),
					'shapla_one_fourth_last'   => __( 'One Fourth Last', 'shapla' ),
					'shapla_three_fourth'      => __( 'Three Fourth', 'shapla' ),
					'shapla_three_fourth_last' => __( 'Three Fourth Last', 'shapla' ),
					'shapla_one_fifth'         => __( 'One Fifth', 'shapla' ),
					'shapla_one_fifth_last'    => __( 'One Fifth Last', 'shapla' ),
					'shapla_two_fifth'         => __( 'Two Fifth', 'shapla' ),
					'shapla_two_fifth_last'    => __( 'Two Fifth Last', 'shapla' ),
					'shapla_three_fifth'       => __( 'Three Fifth', 'shapla' ),
					'shapla_three_fifth_last'  => __( 'Three Fifth Last', 'shapla' ),
					'shapla_four_fifth'        => __( 'Four Fifth', 'shapla' ),
					'shapla_four_fifth_last'   => __( 'Four Fifth Last', 'shapla' ),
					'shapla_one_sixth'         => __( 'One Sixth', 'shapla' ),
					'shapla_one_sixth_last'    => __( 'One Sixth Last', 'shapla' ),
					'shapla_five_sixth'        => __( 'Five Sixth', 'shapla' ),
					'shapla_five_sixth_last'   => __( 'Five Sixth Last', 'shapla' )
				)
			),
			'content' => array(
				'std'   => '',
				'type'  => 'textarea',
				'label' => __('Column Content', 'shapla'),
				'desc'  => __('Add the column content.', 'shapla'),
			)
		),
		'shortcode'    => '[{{column}}]{{content}}[/{{column}}] ',
		'clone_button' => __('Add Column', 'shapla')
	)
);

$shapla_shortcodes['divider'] = array(
	'no_preview' => true,
	'params'     => array(
		'style' => array(
			'type'    => 'select',
			'label'   => __( 'Divider', 'shapla' ),
			'desc'    => __( 'Select the style of the Divider', 'shapla' ),
			'options' => array(
				'plain'  => __( 'Plain', 'shapla' ),
				'strong' => __( 'Strong', 'shapla' ),
				'double' => __( 'Double', 'shapla' ),
				'dashed' => __( 'Dashed', 'shapla' ),
				'dotted' => __( 'Dotted', 'shapla' )
			)
		),
	),
	'shortcode'   => '[shapla_divider style="{{style}}"]',
	'popup_title' => __( 'Insert Divider', 'shapla' )
);

$shapla_shortcodes['intro'] = array(
	'no_preview' => true,
	'params'     => array(
		'content' => array(
			'type'  => 'textarea',
			'label' => __( 'Intro Text', 'shapla' ),
			'desc'  => __( 'Enter the intro text.', 'shapla' ),
			'std'   => 'Intro Text'
		),
	),
	'shortcode'   => '[shapla_intro]{{content}}[/shapla_intro]',
	'popup_title' => __( 'Insert Author Shortcode', 'shapla' )
);

$shapla_shortcodes['tabs'] = array(
	'params' => array(
		'style' => array(
			'type'    => 'select',
			'label'   => __('Tabs Style', 'shapla'),
			'desc'    => __('Select the tabs&lsquo;s style', 'shapla'),
			'options' => array(
				'normal' => __( 'Normal', 'shapla' ),
				'stroke' => __( 'Stroke', 'shapla' ),
			)
		)
	),
	'no_preview'  => true,
	'shortcode'   => '[shapla_tabs style="{{style}}"]{{child_shortcode}} [/shapla_tabs]',
	'popup_title' => __( 'Insert Tab Shortcode', 'shapla' ),
	'child_shortcode' => array(
		'params' => array(
			'title' => array(
				'std'   => 'Title',
				'type'  => 'text',
				'label' => __( 'Tab Title', 'shapla' ),
				'desc'  => __( 'Title of the tab', 'shapla' ),
			),
			'content' => array(
				'std'     => 'Tab Content',
				'type'    => 'textarea',
				'label'   => __( 'Tab Content', 'shapla' ),
				'desc'    => __( 'Add the tabs content', 'shapla' )
			)
		),
		'shortcode'    => '[shapla_tab title="{{title}}"]{{content}}[/shapla_tab]',
		'clone_button' => __( 'Add Tab', 'shapla' )
	)
);

$shapla_shortcodes['dropcap'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type'    => 'select',
			'label'   => __('Dropcap Style', 'shapla'),
			'desc'    => __('Select the dropcap&lsquo;s style', 'shapla'),
			'options' => array(
				'normal' => __( 'Normal', 'shapla' ),
				'squared' => __( 'Squared', 'shapla' ),
			)
		),
		'content' => array(
			'std'   => 'D',
			'type'  => 'text',
			'label' => __( 'Dropcap Text', 'shapla' ),
			'desc'  => __( 'Enter the dropcap&lsquo;s text', 'shapla' )
		),
		'size' => array(
			'std'   => '50px',
			'type'  => 'text',
			'label' => __( 'Font Size', 'shapla' ),
			'desc'  => __( 'Enter the font&lsquo;s size in px, em or %', 'shapla' ),
		),
	),
	'shortcode'   => '[shapla_dropcap font_size="{{size}}" style="{{style}}"]{{content}}[/shapla_dropcap]',
	'popup_title' => __( 'Insert Dropcap Shortcode', 'shapla' )
);

$shapla_shortcodes['image'] = array(
	'no_preview' => true,
	'params' => array(
		'src' => array(
			'std'   => '',
			'type'  => 'image',
			'label' => __( 'Image', 'shapla' ),
			'desc'  => __( 'Choose your image', 'shapla' )
		),
		'style' => array(
			'type'    => 'select',
			'label'   => __('Image Filter', 'shapla'),
			'desc'    => __('Select the CSS3 image filter style', 'shapla'),
			'std'     => 'no-filter',
			'options' => array(
				'no-filter'  => __( 'No Filter', 'shapla' ),
				'grayscale'  => __( 'Grayscale', 'shapla' ),
				'sepia'      => __( 'Sepia', 'shapla' ),
				'blur'       => __( 'Blur', 'shapla' ),
				'hue-rotate' => __( 'Hue Rotate', 'shapla' ),
				'contrast'   => __( 'Contrast', 'shapla' ),
				'brightness' => __( 'Brightness', 'shapla' ),
				'invert'     => __( 'Invert', 'shapla' ),
			)
		),
		'alignment' => array(
			'type'    => 'select',
			'label'   => __('Alignment', 'shapla'),
			'desc'    => __('Choose Image Alignment', 'shapla'),
			'std'     => 'none',
			'options' => array(
				'none'   => __( 'None', 'shapla' ),
				'left'   => __( 'Left', 'shapla' ),
				'center' => __( 'Center', 'shapla' ),
				'right'  => __( 'Right', 'shapla' )
			)
		),
		'url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'URL', 'shapla' ),
			'desc' => __( 'Enter the URL where image should be linked (optional)', 'shapla' )
		)
	),
	'shortcode'   => '[shapla_image style="{{style}}" src="{{src}}" alignment="{{alignment}}" url="{{url}}"]',
	'popup_title' => __( 'Insert Image Shortcode', 'shapla' )
);


$shapla_shortcodes['video'] = array(
	'no_preview' => true,
	'params' => array(
		'src' => array(
			'std'   => '',
			'type'  => 'video',
			'label' => __( 'Choose Video', 'shapla' ),
			'desc'  => __( 'Either upload a new video, choose an existing video from your media library or link to a video by URL. <br><br>', 'shapla' ) . sprintf( __('A list of all shortcode video services can be found on %s.<br>', 'shapla' ), '<a target="_blank" href="//codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F">WordPress.org</a>.<br><br>Working examples, in case you want to use an external service:<br><strong>http://vimeo.com/18439821</strong><br/><strong>http://www.youtube.com/watch?v=G0k3kHtyoqc</strong>' )
		)
	),
	'shortcode' => '[shapla_video src="{{src}}"]',
	'popup_title' => __( 'Insert Video Shortcode', 'shapla' )
);

$shapla_shortcodes['icon'] = array(
	'no_preview' => true,
	'params' => array(
		'icon' => array(
			'std'   => '',
			'type'  => 'icons',
			'label' => __( 'Icon', 'shapla' ),
			'desc'  => __( 'Choose an icon', 'shapla' )
		),
		'url' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'URL', 'shapla' ),
			'desc'  => __( 'Enter the URL where icon should be linked (optional)', 'shapla' )
		),
		'new_window' => array(
			'type'    => 'select',
			'label'   => __( 'Open in new window', 'shapla' ),
			'desc'    => __( 'Do you want to open the link in a new window?', 'shapla' ),
			'options' => array(
				'no'  => __( 'No', 'shapla' ),
				'yes' => __( 'Yes', 'shapla' ),
			)
		),
		'size' => array(
			'std' => '50px',
			'type' => 'text',
			'label' => __( 'Font Size', 'shapla' ),
			'desc' => __( 'Enter the icon&lsquo;s font size in px, em or %', 'shapla' ),
		)
	),
	'shortcode' => '[shapla_icon icon="{{icon}}" url="{{url}}" size="{{size}}" new_window="{{new_window}}"]',
	'popup_title' => __( 'Insert Icon Shortcode', 'shapla' )
);

$shapla_shortcodes['map'] = array(
	'no_preview' => true,
	'params' => array(
		'lat' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'Latitude', 'shapla' ),
			'desc'  => __( 'Enter the place latitude coordinate. E.g.: 37.42200', 'shapla' )
		),
		'long' => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'Longitude', 'shapla' ),
			'desc'  => sprintf( __( 'Enter the place longitude coordinate. E.g.: -122.08395. You may find longitude and latitude <a href="%1$s" target="_blank">here</a>.', 'shapla' ), esc_url('http://universimmedia.pagesperso-orange.fr/geo/loc.htm') )
		),
		'width' => array(
			'std'   => '100%',
			'type'  => 'text',
			'label' => __( 'Width', 'shapla' ),
			'desc'  => __( 'Enter the map width.', 'shapla' )
		),
		'height' => array(
			'std'   => '350px',
			'type'  => 'text',
			'label' => __( 'Height', 'shapla' ),
			'desc'  => __( 'Enter the map height.', 'shapla' )
		),
		'zoom' => array(
			'std'   => '15',
			'type'  => 'text',
			'label' => __( 'Zoom Level', 'shapla' ),
			'desc'  => __( 'Enter the map zoom level between 0-21. Highest value zooms in and lowest zooms out.', 'shapla' )
		),
		'style' => array(
			'std'     => 'none',
			'type'    => 'select',
			'label'   => __( 'Map Style', 'shapla' ),
			'desc'    => __( 'Select from a list of predefined map styles.', 'shapla' ),
			'options' => array(
				'none'             => __( 'None', 'shapla' ),
				'pale_dawn'        => __( 'Pale Dawn', 'shapla' ),
				'subtle_grayscale' => __( 'Subtle Grayscale', 'shapla' ),
				'bright_bubbly'    => __( 'Bright & Bubbly', 'shapla' ),
				'greyscale'        => __( 'Greyscale', 'shapla' ),
				'mixed'            => __( 'Mixed', 'shapla' )
			)
		),
	),
	'shortcode'   => '[shapla_map lat="{{lat}}" long="{{long}}" width="{{width}}" height="{{height}}" style="{{style}}" zoom="{{zoom}}"]',
	'popup_title' => __( 'Insert Google Map Shortcode', 'shapla' )
);
