<?php /*

**************************************************************************

Plugin Name:  Enable oEmbed Discovery
Plugin URI:   http://www.viper007bond.com/wordpress-plugins/other-plugins/enable-oembed-discovery/
Version:      1.0.0
Description:  Enables the oEmbed discovery ability in WordPress 2.9's <a href="http://codex.wordpress.org/Embeds">embed feature</a>. Only applies to users with the <code>unfiltered_html</code> capability (Administrators and Editors by default). oEmbed discovery allows the embedding of content from unknown sites that support the oEmbed discovery tag. Please be careful not to embed content from any malicious site!
Author:       Viper007Bond
Author URI:   http://www.viper007bond.com/

**************************************************************************/

add_filter( 'embed_oembed_discover', 'viper_enable_oembed_discovery' );

function viper_enable_oembed_discovery() {
	return true;
}

// Yeah, that's it. :)

?>