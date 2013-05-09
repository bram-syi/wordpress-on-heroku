=== Technorati Full Feeds ===
Contributors: miqrogroove
Tags: Technorati, full text, summary, excerpt, full feed, directory, authority, feed, rss
Requires at least: 2.2
Tested up to: 3.5
Stable tag: 1.6

Overrides the Summary/Full Text option when Technorati fetches your feeds, and forces Full Text mode.

== Description ==

Technorati now requires Full Text feeds to participate in their directory and "topical authority" systems.  If your blog uses the Summary feed option, then Technorati might ask you to change that option, or to make an exception so that Technorati can always see the Full Text.  This plugin eliminates the work for you!  Just install, activate, and forget about it.

== Installation ==

1. Upload the `technorati-full-feeds` directory to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. If you are using a caching plugin, there is important information for you in the readme file.

This is a zero-configuration plugin.  There are no settings.

Deactivation removes everything except the files you uploaded.  There is no "uninstall" necessary.

== Changelog ==

= 1.6 =
* Compatibility improvement, released 21 November 2012.
* Added support for WP Super Cache.
* Removed i18n support.  Nobody was using it.
* WordPress 3.5-beta3 tested.

= 1.5 =
* Extra permissions checking, released 28 May 2011.
* WordPress 3.0.6 and 3.1.3 tested.

= 1.4 =
* Added i18n support, released 30 December 2009.
* i18n plugin features are intended for administrative use.
* Technorati does not support non-English feeds at this time.
* WordPress 2.9.1-RC1 tested.

= 1.3 =
* New features, released 17 December 2009.
* Added a feedback display on the Plugins screen.
* Admins can now see the last visit time of the bot.
* Upgraders, you will see "not visited" until the next feed fetch.
* This feature is only available in WordPress 2.8 and higher.
* Older versions should degrade gracefully.  Still 2.2 compatible.
* WordPress 2.9-RC1 tested.

= 1.2 =
* Fixed a math bug, 10 December 2009.

= 1.1 =
* Better bot ID logic added after discussion, 4 December 2009.
* Uses the official Technorati IP address range instead of bot headers.

= 1.0 =
* First version, released 4 December 2009

== Upgrade Notice ==

= 1.2 =
Version 1.1 did not work on 64-bit servers.  The plugin failed with no errors.  Sorry about that.  Version 1.2 is the fix.

== Cache Compatibility ==

= WP Super Cache =

As of version 1.6, Technorati Full Feeds will attempt to automatically install a sub-plugin for WP Super Cache.  There are several possible situations where this might not happen automatically.  For example, if you activate WP Super Cache after Technorati Full Feeds, or if your security settings prevent the file from being copied.

You need to confirm that my file `technorati-no-super-cache.php` gets copied to the Cache Plugins directory, which is `/wp-content/plugins/wp-super-cache/plugins/` by default.  If the file is missing, you can upload it to that directory, and no further steps are necessary.

If this file is missing, what will happen is that the Technorati "last seen" time displayed on the WordPress Plugins page will never update.  This is because Technorati is receiving the cached (summary only) feed file.

= Other Plugins =

Technorati Full Feeds has *not* been tested with any other caching systems, and is likely to fail in the presence of any feed caching.
