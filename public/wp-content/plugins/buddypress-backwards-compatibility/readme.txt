=== BuddyPress Backwards Compatibility ===
Contributors: The BuddyPress Community
Tags: buddypress, backwards compatibility
Requires at least: 2.9.1
Tested up to: 2.9.1
Stable Tag: 0.6

Code needed for backwards compatibility with previous versions of BuddyPress. Contains original themes, wire component, status updates, and functions that have been renamed or replaced.

== Description ==

Code needed for backwards compatibility with previous versions of BuddyPress. Contains wire component, status updates, and functions that have been renamed or replaced.

It also contains the original bp-classic and bp-sn-parent themes from 1.1, which have been updated to included the new code from BuddyPress 1.2.

Use this plugin if your theme relies on functions or components that existed in BuddyPress versions less than 1.2.

This plugin includes the wire and status update components. These components were merged into the activity stream in BuddyPress 1.2.

== Installation ==

1. Upload `/buddypress-backwards-compatibility/` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. A menu item named "Legacy Components" is added to the BuddyPress menu. Use this to turn on backwards compatibility support for those individual components.

== Frequently Asked Questions ==

= Why would I use this plugin =
1. If you want to use the wire component.
2. If you want to have dedicated status updates outside of activity stream updates.
3. If your theme uses functions that were removed or renamed in the lastest version of BuddyPress
4. If you are using plugins that have not updated to support the latest version of BuddyPress.

= Does this plugin modify my existing data? =
No, but if using the wire or status updates components, it will attempt to upgrade or create the necessary tables those components need.

== Changelog ==

= 0.6 =
* Added support for BuddyPress 1.2 final.

= 0.5.3 =
* Add missing friend count functions

= 0.5.2 =
* Included original bp-class and bp-sn-parent themes and register the new bp-themes path

= 0.5.1 =
* Set BP_CLASSIC_TEMPLATE_STRUCTURE to true by default

= 0.5 =
* Added deprecated activity widget and better wire and group template tag support.

= 0.1 =
* Initial upload of files to WP plugin repository