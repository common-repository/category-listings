=== Plugin Name ===
Contributors: clubwordpress
Donate link: http://www.club-wp.com/category-listings-wordpress-plugin/
Tags: categories, category
Requires at least: 3.1.0
Tested up to: 3.1.0
Stable tag: 1.2

Outputs a list of recent posts from the category of the post that is being viewed.

== Description ==

The plugin outputs the most recent posts from the category of the post that is currently being displayed. It is possible to configure the number of posts to be output, the HTML before and after the whole of the output, the HTML before and after the post title and the HTML before and after the post excerpt.

== Installation ==

1. Upload `category-listings.php` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Configure your output in `Settings->Category Listings` menu.
4. Either add the shortcode 'category-listing' in you posts where you want the listings to appear or place '<?php echo output_category_listing(); ?>' into you theme where you want the categories to be output.

== Frequently Asked Questions ==

= Why can't I use a widget for outputting the category posts? =

This is in development.

== Screenshots ==

== Changelog ==

= 1.0 =
* A change since the previous version.

= 1.1 =
* Added the shortcode 'category-listing' which gets the category listings. This means that the theme does not need to be altered for the plugin to work.

= 1.1 =
* Fixed the permalink for the listing links.

== Upgrade Notice ==

= 1.0 =
No upgrades yet.

= 1.1 =
Added the 'category-listing' shortcode to output the category listings.

