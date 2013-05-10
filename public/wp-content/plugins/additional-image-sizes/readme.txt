=== Additional image sizes ===
Author: waltervos
Tags: images, image management, image sizes
Requires at least: 2.5
Tested up to: 2.9
Stable tag: 1.0.2

Allows you to create additional image sizes (in addition to the thumbnail, medium and large size that are default) for your blog.

== Description ==

With this plugin, users can add and remove intermediate image sizes. The plugin can also make copies of existing images with these new sizes. You will be able to use the images in your posts or use them in your theme with the 'Get the image' plugin or with WordPress 2.9's `the_post_image` feature.

== Installation ==

1. Upload the `additional-image-sizes` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the `Plugins` menu in WordPress
1. Now you'll see a new menu item in the `Media` menu where you can add additional sizes and so forth

== Changelog ==

= 1.0.2 =
* Minor bugfixes, sorry about all the updates

= 1.0.1 =
* 1.0 didn't preserve image sizes from 0.1 installs, now it does (and recovers those that were lost)

= 1.0 =
* Several small bugfixes
* thumbnail, medium and large can no longer be used as the name for a size (WordPress itself uses those already)
* Width or height can now be left empty
* Slight improvements to the user interface
* Prevent timeouts when regenerating copies of existing sizes

= 0.1 =
Very first release, features are:

*   Adding additional image sizes.
*   Generate copies of additional image sizes.
*   Use the additional sizes in your posts or pages from the `Add an Image` screen.