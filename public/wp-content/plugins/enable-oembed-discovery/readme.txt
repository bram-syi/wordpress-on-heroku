=== Enable oEmbed Discovery ===
Contributors: Viper007Bond
Donate link: http://www.viper007bond.com/donate/
Tags: embed, embeds, oembed
Requires at least: 2.9
Tested up to: 2.9
Stable tag: trunk

Enable oEmbed discovery for WordPress 2.9.

== Description ==

This plugin enables the oEmbed discovery ability in WordPress 2.9's [embed feature](http://codex.wordpress.org/Embeds). It only applies to users with the `unfiltered_html` capability (Administrators and Editors by default).

= What is oEmbed discovery? =

Website owners can add a bit of HTML to their head that says where their oEmbed provider is located. This allows consumers such as WordPress to embed things from their website without WordPress specifically knowing about their website before hand.

However this is disabled in WordPress by default to prevent someone (either on purpose or by accident) from embedding content from a malicious website.

= So oEmbed discovery is bad? =

No, it's just powerful and has risks. Whatever HTML the remote website provides is used directly. Normally that's fine, but they could also provide HTML that is bad for you and your visitors. So it's best if you know what you're doing which is why it's disabled in WordPress by default.

== Installation ==

###Updgrading From A Previous Version###

To upgrade from a previous version of this plugin, delete the entire folder and files from the previous version of the plugin and then follow the installation instructions below.

###Installing The Plugin###

Extract all files from the ZIP file, making sure to keep the file structure intact, and then upload it to `/wp-content/plugins/`. Then just visit your admin area and activate the plugin. That's it!

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== ChangeLog ==

= Version 1.0.0 =

* Initial release.