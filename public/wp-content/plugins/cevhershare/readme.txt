=== CevherShare ===
Contributors: wpusta
Donate link: http://www.cevhershare.com/donate
Plugin URL: http://www.cevhershare.com
Tags: sharing, social networks, marketing, social media, cevher, cevhershare, sharebox, sharebar, facebook, twitter, email, digg, buzz, google, google plus, plusone, google+, yahoo, like
Current version: 2.1
Requires at least: 2.0
Tested up to: 3.4
Stable tag: 3.3.1


CevherShare adds to your site dynamic and fully customizable vertical box to the left side of your post that contains links/buttons to popular social networking sites.


== Description ==

CevherShare is allows to add dynamic and fully customizable vertical sharing box to the left side of your post that contains links/buttons to popular social networking sites. (FaceBook, Twitter, Digg, Buzz, Yahoo and etc.) For wide pages, a vertical bar with popular sharing icons appears on the left of your post. If the page is resized below 1000px (default), the vertical bar disappears and a horizontal cevhershare appears under the post title.

Big Buttons are designed for vertical CevherShare to the left of the post, while the Small Buttons are used in the horizontal CevherShare that appears under the post title (by default) if the width of the page is less than 1000px (or whatever value you set).

When Auto mode (enabled in settings) is ON, then CevherShare will added to your site automatically.  When Auto mode is OFF, then you have to add manually cevhershare codes to your site template files:

* Vertical (next to post) CevherShare: `<?php cevhershare(); ?>`
* Horizontal CevherShare: `<?php cevhershare_horizontal(); ?>`

Also you can use individual button code in any template: (Define your button name and also define size big or small): `<?php cevhershare_button('name','size'); ?>`

Full instructions and example can be found at: http://www.cevhershare.com

Follow me on Twitter: http://twitter.com/wpusta

**Also, if you are using our plugin and like it, then please rate it!  --->**

**Supported Languages**
* Azerbaijan
* English
* Russian
* Turkish
* For your language contact with us. http://www.cevhershare.com/contact

== Installation ==

Upload the CevherShare plugin to your plugins directory, activate it and it will work with default settings. You can customize settings for your site!


== Screenshots ==

1. The plugin in action (vertical bar). If page is resized to less than 1000px, a vertical share bar appears under title.
2. Main Plugin Page
3. Plugin Settings Page
4. Edit button page


== Frequently Asked Questions ==

= Why doesn't the vertical cevhershare (next to post) work? =

First, be sure the plugin istalled properly and check that you're using plugin with manual mode or auto mode. If you're using manual mode cevhershare codes must added to your single.php template. If you're using auto mode, verify that the cevhershare list is being added to your post in the source code (look for `<ul class='cevhershare'>`).  If it's not in the source, there is a problem with the plugin settings or implementation.  If it is there, chances are the error is CSS-based - make sure the parent container element of the post (or the post element itself) does not have the `overflow:hidden;` CSS property applied to it, as it may sometimes render the cevhershare invisible.

= How can I setup cevhershare to view on the right of my site? =

You can change Right Offset in the settings page of the plugin - this should include the total width of your sidebar or whatever elements are between your post and the right most edge of your blog.


== Changelog ==
= 2.1 =
* Change ability background color (HEX color) integrated with color picker.
* Added sharethis button and fixed Facebook share buttons counting bug. 

= 2.0.1 =
* Security fixed
* Added Facebook like button

= 2.0 =
* Upgraded and optimized all of codes and added Google+

= 1.2.2 =
* Added background transparent option.

= 1.2.1 =
* Added Linkedin share button.
* Changed edit & delete links to pretty icons.

= 1.2.0 =
* Added Language options (Russian and Turkish languages supported)
* Fixed CSS li background bug. (Some Themes was show their bullet before)
* Added Thanks link options for translators. You can also translate to your language.
* Optimized language bundles.

= 1.1.0 =
* Added Language options, (Azerbaijan, English languages supported)


= 1.0 =
* Added ability to enable/disable buttons and mass delete buttons
* Added Facebook, Twitter, Buzz, Yahoo, Email, Dzone, Reddit, Stumbleupon buttons
* Added option to customize width of CevherShare
* Added option to set Twitter username and use that in buttons using [twitter] code
* You can now add CevherShare to posts or pages in the settings
* You can set it left or right site

== Upgrade Notice == 
= 1.2.0 =
* Fixed CSS li bugs. Upgrade for more usefull version.