=== CSS Cache Buster ===
Contributors: blogologist
Donate link: http://www.alistercameron.com/
Tags: css, stylesheet, cache, cache buster, css cache buster, caching, cacheing
Requires at least: 1.2.1
Tested up to: 2.7
Stable tag: 1.0

Always deliver the latest version of your CSS stylesheet to browsers. All caching problems solved.

== Description ==

CSS Cache Buster does one very simple but very powerful thing: it ensures that your 
Wordpress blog delivers the very latest version of your CSS stylesheet to your 
visitors, no matter how often or how rarely your edit for CSS.

You no longer need to worry that you've made a CSS change that others won't see 
because of caching issues at their end.

Technically, this plugin automatically appends a last-modified date/timestamp to 
the end of your stylesheet url.

And since this feature is applied as a filter, you don't have to touch your theme 
files. It just works.

Simple!

== Installation ==

The CSS Cache Buster plugin is installed in the standard way:

1. Upload `css-cache-buster.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. That's it! Nothing else to do. Breathe a long sigh.

== Frequently Asked Questions ==

= It doesn't seem to be working. Nothing has changed for me. What's wrong? =

This plugin specifically looks for the bloginfo('stylesheet_url') function call, which 
is almost universally used in Wordpress themes to call the stylesheet. If your theme
calls the stylesheet without using this function call, then this plugin will not work.

= I think I found a bug. What do I do? =

Fix it if you know how. Either way, drop a comment on my blog and let me know!

== Screenshots ==

There are no screenshots for this plugin. The magic happens behind the scenes!