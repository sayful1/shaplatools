=== ShaplaTools ===
Contributors: sayful
Tags: custom post type, widget, shortcode, twitter, images, image
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 1.4.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

ShaplaTools is a powerful plugin to extend functionality to your WordPress themes.

== Description ==

ShaplaTools is a powerful plugin to extend functionality to your WordPress themes offering shortcodes, FontAwesome icons and useful widgets.

= Widgets =

* Dribbble Shots
* Facebook Like Box
* Flickr Photos
* Instagram Photos
* Twitter Feed

= Shortcodes =

* Alerts
* Buttons ( optionally, with font icons )
* Columns
* Divider / Horizontal Ruler
* Dropcaps
* Intro Text
* Tabs
* Toggle
* Font Icons by Font Awesome
* Google Maps with 5 predefined styles
* Image with CSS3 filters
* Videos ( supports Embeds )

== Installation ==

Installing the plugins is just like installing other WordPress plugins. If you don't know how to install plugins, please review the three options below:

= Install by Search =

* From your WordPress dashboard, choose 'Add New' under the 'Plugins' category.
* Search for 'ShaplaTools' a plugin will come called 'ShaplaTools by Sayful Islam' and Click 'Install Now' and confirm your installation by clicking 'ok'
* The plugin will download and install. Just click 'Activate Plugin' to activate it.

= Install by ZIP File =

* From your WordPress dashboard, choose 'Add New' under the 'Plugins' category.
* Select 'Upload' from the set of links at the top of the page (the second link)
* From here, browse for the zip file included in your plugin titled 'shaplatools.zip' and click the 'Install Now' button
* Once installation is complete, activate the plugin to enable its features.

= Install by FTP =

* Find the directory titles 'shaplatools' and upload it and all files within to the plugins directory of your WordPress install (WORDPRESS-DIRECTORY/wp-content/plugins/) [e.g. www.yourdomain.com/wp-content/plugins/]
* From your WordPress dashboard, choose 'Installed Plugins' option under the 'Plugins' category
* Locate the newly added plugin and click on the \'Activate\' link to enable its features.

== Changelog ==

= version 1.4.0 =
* Tweak  	- Add support for Font Awesome 5 Free.
* Tweak  	- Update twitter feed widget by adding support for transient, cached.
* Tweak  	- Update Facebook Like Box Widget.
* Tweak  	- Update flickr widget.
* Tweak  	- Update instagram widget.
* Tweak  	- Update google map shortcode.
* Fixed  	- Fixed dribbble widget not working as it require Access Token.
* Removed  	- Removed auto complete search suggestion feature from core.
* Removed  	- Removed google-analytics script option.
* Removed  	- Removed team post type.
* Removed  	- Removed testimonial post type.
* Removed  	- Removed retina image generation.
* Dev  	    - Add Widget Wrapper class.
* Dev  	    - Update setting page API to add section.
* Dev  	    - Twitter API, Facebook API, Dribbble API, Google Map API settings now on setting page.
* Dev  	    - Update core code.

= version 1.3.1 =
* Fixed  	- Adding multiple slides at same page
* Fixed  	- issue for backward compatibility for alert and button at new version.

= version 1.3.0 =
* New 		- Google map shortcode now supports map type to choose between Roadmap, Satellite, Hybrid, and Terrain. A new settings for Google Maps API key under Settings > ShaplaTools
* Updated 	- FontAwesome library v4.7.0
* Updated 	- Retina.js v2.0.0
* Updated 	- Alerts Shortcode has been modified by adding many new colors.
* Updated 	- Columns Shortcode has been changed to bootstrap like 12 columns system. also backed up previous 5 columns system.
* Updated 	- Buttons Shortcode has been modified by adding many new colors, hover colors, button shape, new button size.
* Fixed 	- Error of file width and height for retina image
* Fixed 	- Typeahead autocomplete search javaScript error
* Fixed 	- Instagram widget to work with new API
* Fixed 	- Dribbble widget feed URL causing widget to fail

= version 1.2.2 =
* Change - replacing TwitterAPIExchange.php with TwitterWP.php for Twitter Widget for better working with WordPress..

= version 1.2.1 =
* Removed - plugin redirection after activation.

= version 1.2.0 =
* Updated - FontAwesome library v4.4.0
* Tweak - Divider shortcode is now merged with default "Horizontal Line" editor button
* Tweak - Intro and Alert shortcodes are now editor styles, as "Formats" in editor

= version 1.1.2 =
* Change - Upgrade Facebook Like Box Widget to Facebook Page Plugin 2.4 API

= version 1.1.1 =
* Fixed - Error of constructor method for WP_Widget in WordPress 4.3

= version 1.1.0 =
* New - Rewritten Instagram widget
* New - Four TinyMCE button for portfolio, team, testimonial, feature

= version 1.0.0 =
* Initial release 
