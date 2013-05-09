=== SEO No Duplicate ===
Tags: seo, google, canonical, duplicate, content
Contributors: madeinthayaland
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=thaya.kareeson@gmail.com&currency_code=USD&amount=&return=&item_name=Donate+a+cup+of+coffee+or+two+to+support+SEO+No+Duplicate+WordPress+plugin.
Requires at least: 2.7
Tested up to: 3.3.2
Stable Tag: 0.5.0

This plugin helps you manage your search engine duplicate content, by setting your post page's canonical to the permalink.

== Description ==
This simple plugin helps you easily tell the search engine bots the preferred
version of a page by specifying the canonical properly within your head tag.

Why would you do such a thing? Simply because many black-hat SEO folks abuse
duplicate content to gain search ranking and search engine try really hard to
detect and penalize them. The last thing you want to do is present yourself as
an SEO spammer to search engines.

*WordPress 2.9+* includes rel="canonical" header settings by default but the
feature only works properly on singular posts (not category or archive pages).
So for example:

The second page of your homepage should actually have its canonical URL set to:
  "http://example.com" instead of "http://example.com/page/2"

Using this plugin solves the above issue in WordPress 2.9+.

== Features ==

* Automatically get rid of search engine duplicate content problems.
* Support all types of WordPress pages.
* Available canonical overrides per post/page via custom fields.
* Easy implementation.  Just activate and go.

== Installation ==

1. Upload the plugin to your plugins folder: 'wp-content/plugins/'
2. Activate the 'SEO No Duplicate' plugin from the Plugins admin panel.

== Frequently Asked Questions ==

= How do I override a post or page canonical with a custom URL? =
You can override the canonical URL of each post or page, by specifying the
URL in the "SEO No Duplicate" box on your post edit page.

= I can't get it to work! I'm frustrated! =
Please take a look at documentation available on the
[plugin page](http://omninoggin.com/wordpress-plugins/seo-no-duplicate-wordpress-plugin/)
to see if any of them can help you.  If not, feel free to post your issues
on the appropriate [plugin support forum](http://omninoggin.com/forum).
I will try my best to help you resolve any issues that you are having.

== Credits ==
Permalink detection algorithm shameless ripped from Joose De Valk's
[Canonical](http://yoast.com/wordpress/canonical/) plugin.

== Changelog ==

= 0.5.0 =
* Tested with 3.3.2
* No longer showing attribution link by default.
* Changed donation phrasing.

= 0.4.4 =
* Make canonical box show up on pages.
* Redo admin page a bit.

= 0.4.3 =
* More code cleanup

= 0.4.2 =
* Fixed translation
* Updated .pot file
* Code cleanup

= 0.4.1 =
* Fix plugin activation error
* Added .pot file for translation

= 0.4 =
* WordPress 2.9 compatibility
* Canonical override box on post/page edit page.
* Code cleanup
* Added options page

= 0.3.2 =
* Removed OMNINOGGIN dashboard widget.

= 0.3.1 =
* Fixed array_slice() warning in the admin dashboard.
* Fixed version check to not break page when $wp_version is empty.

= 0.3 =
* Added version check

= 0.2 =
* Added support for all types of WordPress pages
* Added canonical user overrides via custom fields

= 0.1 =
* Initial release

== Screenshots ==

1. Available manual canonical override.
