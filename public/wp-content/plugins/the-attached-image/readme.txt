=== The Attached Image ===
Contributors: veneficusunus, Nick Benson; PressEnter Creative, RogueDeals.com, Upekshapriya
Donate link: http://return-true.com/donations/
Tags: images, attachments, posts
Requires at least: 2.5
Tested up to: 3.3.2
Stable tag: 2.6.3.1

The Attached Image is a simple plugin that packs quite a punch. It shows the first image attached to the current post.

== Description ==

The Attached Image is a simple plugin that packs quite a punch. It shows the first image attached to the current post. It was inspired by a plugin wrote by Kaf Oseo, but when support was stopped &amp; a recent upgrade of WordPress meant it didn't work exactly like it used to I decided to take on the challenge of remaking it using the new WordPress functions available.

Features include an options page to easily customise how the plugin works, the ability to override those options through the use of legacy parameters entered via a fairly friendly query string system. All the plugin requires is that you call it within the loop.

There are limited instructions of how to install this plugin here on Wordpress.org under the installation tab. It is however advisable to check the detailed installation instructions provided a my [website](http://return-true.com/2008/12/wordpress-plugin-the-attached-image/ "Full installation instructions & more info")

Please report all bugs by visiting my [website](http://return-true.com/2008/12/wordpress-plugin-the-attached-image/ "Report bugs here.") & dropping me a comment or by sending me an email to pablorobinson[at]gmail[dot]com. Thank you.

== Installation ==

1. Unzip the zip file.
1. place the folder into the `wp-content/plugins` folder.
1. Place `<?php the_attached_image(); ?>` in your template.
1. Open your Appearence menu and click The Attached Image. There you will find all the options to customise how The Attached Image works.
1. Check my [website](http://return-true.com/2008/12/wordpress-plugin-the-attached-image/ "Full installation instructions & more info") for more information on what each option does, or click documentation in the options page for full documentation on each option & how to use legacy parameters.

== Frequently Asked Questions ==

= What happened to the FAQ? =

Well most of the questions asked are now irrelevant as there is a detailed post with instructions of what the plugin does, how to install it, what the options do & other things at my [website](http://return-true.com/2008/12/wordpress-plugin-the-attached-image/ "Answers to all your questions about The Attached Image").

= What if that post doesn't answer my question? =

You can ask me it by leaving a comment on that post I linked to in the last question, you can send an email via the contact form on that website or you can send me an email straight to pablorobinson[at]gmail[dot]com.

== Changelog ==


= 2.6.3.1 =
* Fixed syntax error. Thanks to Bill Hopkins.

= 2.6.3 =
* Add query object passthrough for have_attached_image() function.

= 2.6.2 =
* WordPress version compat update.

= 2.6.1 =
* Fixed minor bug with custom image links via custom fields.

= 2.6 =
* ---

= 2.5.9.4 =
* Added the correct way to grab width & height from the info returned by WP.

= 2.5.9.3 =
* Ahem! Nothing to see here. Making stupid mistakes.

= 2.5.9.2 =
* Added cleaner code for displaying full image contributed by Upekshapriya.

= 2.5.9.1 =
* Fixed problem with img tag ignoring the disable command when using in post image functionallity.

= 2.5.9 =
* Minor update for asthetics & added donations box to admin after requests for an easy way to support the plugin.

= 2.5.8 =
* Fixed default image problem if used without setting up the admin page.
* Changed custom_img system to use ID of post with custom image instead of attachment ID.
* Added custom field key for use with image order system.

= 2.5.7 =
* Minor case-sensitivity bug

= 2.5.6 =
* Removed RSS feature due to incompatibilities & major flaws.

= 2.5.5 =
* Major overhaul of in_post_image system, including WP thumbnail detector.

= 2.5.4 =
* Added a new option to pick thumbnails sizes when using in post functionality

= 2.5.3 =
* Added a fix that stopped rel attr from being applied only on default link case. Thanks to Nick Benson @ PressEnter Creative for the fix. 

= 2.5.2 =
* Added a fix for return/echo support on in_post_image & added a suggestion from Nick Benson to link to large images if they exist.

= 2.5.1 =
* Added enclosure support to show the first attached image as a media file in WP RSS & RSS2 feeds.

= 2.5 =
* Added fix for custom queries via second function parameter.

= 2.4.9.2 =
* Fixed full image size flaw.

= 2.4.9.1 =
* Added a bug fix for the want image tag. Thanks Eduardo Gonzalez for the fix.

= 2.4.9 =
* Added have_attached_image() for checking if attached image is available.

= 2.4.8 =
* Added a checkbox in options to turn off & on perma functionality for in post image.

= 2.4.7 =
*Changed $post to $wp_query->post to allow for the use of query_posts.

= 2.4.6 =
* Forgot to add custom width & height for external images.

= 2.4.5 =
* Fixed a problem with the option image filename for the alt & title attribs.

= 2.4.4 =
* Added getimagesize for external servers.

= 2.4.3 =
* Added in_post_image feature for Jake Garrison & fixed changelog numbers.

= 2.4.2 =
* Minor bug fix. Silly mistake on options page. Marked height as width on options.

= 2.4.1 =
* Minor bug fix. Width & Height if logic.

= 2.4 =
* Added legacy support to all parameters & added documentation to cover useage.

= 2.3.5 =
* Forgot space after default images alt text. Thanks to rougedeals.com for the spot.

= 2.3.4 =
* Forgot to return output in alt text, also made mistake in if logic for default title/alt.

= 2.3.3 =
* Added more function customisation parameters.

= 2.3.2 =
* Added parameters back in for CSS class and img size so multiple calls to the function can be customised since the options page acts as global.

= 2.3.1 =
* Minor bug fixes to mistakes that should never have been made in the first place.

= 2.3 =
* Added support for changeable image alt & hyperlink title attributes.

= 2.2 =
* Major remodel. Plugin now includes a options page.

= 2.1.1 =
* Minor bug fix for default images.

= 2.1 =
* Added support for adding the rel attribute to the href.Thanks to Dip for the suggestion.

= 2.0.1 =
* Fixed a small bug in the custom link feature. Thanks to Jennifer once more for pointing it out.

= 2.0 =
* Changed the method by which custom field work to one suggested by Jennifer of scriptygoddess.com.

= 1.9 =
* You can now supply a custom link path for the image to point to.

= 1.8 =
* If a description is supplied to the image within WP that will be inserted as the image's alt attribute.

= 1.7 =
* Added image_order option after a request by Steve.

= 1.6.1 =
* Forgot to remove my testing comment & cleaned the header a litle. Thanks to Steve for pointing out the bug.

= 1.6 =
* Added ability to show the full size image if wanted. Suggested by Brian Wood.

= 1.5 =
* Added the ability to choose an image that is not attached to the current post defined in the post loop.

= 1.4.2 =
* Fixed another flaw in the width & height decision system.

= 1.4.1 =
* Fixed flaw in width & height checking for custom fields.

= 1.4 =
* Added the ability to use a custom width & height.

= 1.3 =
* Added ability to choose a default image to show should there be no image to display.

= 1.2 =
* Added the ability to output a link around the image.

= 1.1 =
* Fixed a stupid mistake. Used strict type check for true.