<?php
/*
Plugin Name: Promote RSS Feed Widget
Plugin URI: http://www.anthonymontalbano.com/software/wordpress/promote-rss-feed-widget/
Description: This will add a widget that will give the option to add a variety of RSS feed reader subscription buttons. 
Version: 0.2
Author: Anthony Montalbano
Author URI: http://www.anthonymontalbano.com

Copyright 2009 Anthony Montalbano (me@anthonymontalbano.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

//define plugin url
if ( !defined('WP_PLUGIN_PATH') )
    define( 'WP_PLUGIN_PATH',get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)));
	
//Wordpress action hook to add the widget
add_action('widgets_init', 'prf_widget_init');

//array of available rss feed reader buttons
$feedreaders =  array(
	"google" => array("My Google","google.gif","http://fusion.google.com/add?feedurl="),					 
	"yahoo" => array("My Yahoo","yahoo.gif","http://add.my.yahoo.com/rss?url="),
	"aol" => array("My AOL","aol.gif","http://feeds.my.aol.com/add.jsp?url="),
	"attensa" => array("Attensa","attensa.gif","http://download.attensa.com/app/get_attensa.html?feed="),
	"bitty" => array("Bitty Browser","bitty.gif","http://www.bitty.com/manual/?contenttype=rssfeed&amp;contentvalue="),
	"newsgator" => array("Newsgator","newsgator.gif","http://www.newsgator.com/ngs/subscriber/subext.aspx?url="),
	"newsalloy" => array("NewsAlloy","newsalloy.gif","http://www.newsalloy.com/?rss="),
	"excite" => array("Excite Mix","excite.gif","http://mix.excite.eu/add?feedurl="),
	"rojo" => array("Rojo","rojo.gif","http://www.rojo.com/add-subscription?resource="),
	"netvibes" => array("Netvibes","netvibes.gif","http://www.netvibes.com/subscribe.php?url="),
	"freedictionary" => array("The Free Dictionary","freedictionary.gif","http://www.thefreedictionary.com/_/hp/AddRSS.aspx?"),
	"bloglines" => array("Bloglines","bloglines.gif","http://www.bloglines.com/sub/"),
	"plusmo" => array("Plusmo","plusmo.gif","http://www.plusmo.com/add?url="),
	"friendster" => array("Friendster","friendster.gif","http://www.plusmo.com/add?url="),
	"hi5" => array("Hi5","hi5.gif","http://www.plusmo.com/add?url="),
	"itunes" => array("iTunes","itunes.gif","http://www.plusmo.com/add?url="),
	"zune" => array("Zune","zune.gif","http://www.plusmo.com/add?url="),
	"mymsn" => array("MyMSN","mymsn.gif","http://www.plusmo.com/add?url="),
	"outlook" => array("Outlook","outlook.gif","http://www.plusmo.com/add?url="),
	"xanga" => array("Xanga","xanga.gif","http://www.plusmo.com/add?url="),
	"hubmobile" => array("netomat Hub","hubmobile.gif","http://hub.netomat.net/account/account.autoSubscribe.jspa?urls="),
	"fwicki" => array("fWicki","fwicki.gif","http://www.fwicki.com/users/default.aspx?addfeed="),
	"webwag" => array("Webwag.this","webwag.gif","http://www.webwag.com/wwgthis.php?url="),
	"odeo" => array("Odeo","odeo.gif","http://odeo.com/listen/subscribe?feed="),
	"podcastready" => array("Podcast Ready","podcastready.gif","ttp://www.podcastready.com/oneclick_bookmark.php?url="),
	"podnova" => array("Podnova","podnova.gif","http://www.podnova.com/add.srf?url="),
	"pageflakes" => array("Pageflakes","pageflakes.gif","http://www.pageflakes.com/subscribe.aspx?url=")	
);


function prf_widget_init() {

  //Check if plugin widgets can be registered
  if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
    return;

  //Outputs the rss feed buttons into the sidebar
  function prf_widget_display($args) {
	global $feedreaders;
    extract($args);

    $options = get_option('prf_widget');
	
    $title = htmlspecialchars($options['title'], ENT_QUOTES);
	$customtext = htmlspecialchars($options['customtext'], ENT_QUOTES);
	$feedurl = ($options['feedurl']=="") ? get_bloginfo('rss_url') : $options['feedurl'];
	$selreaders = $options['feedreaders'];

    // Output the widget.
    echo $before_widget . $before_title . $title . $after_title;
	print "<ul>";
	if(in_array('feedicon',$selreaders)) {
		print "<li><a href='".$feedurl."' target=_'blank'><img src=\"".WP_PLUGIN_PATH."/images/feedicon.gif\"> ".$customtext."</a></li>";
	}
	foreach($feedreaders as $fr) {
		if(in_array(key($feedreaders),$selreaders)) {
			print "<li><a href='".$fr[2].$feedurl."' target=_'blank'><img src=\"".WP_PLUGIN_PATH."/images/".$fr[1]."\"></a></li>";
		}
	}	
	print "</ul>";
    echo $after_widget;
  }

  //Admin function to edit the widget
  function prf_widget_admin() {
	global $feedreaders;
	
	$options = get_option('prf_widget');
	if ( $_POST['psfw-submit'] ) {
		//sanitize and format user input
		$options['title'] = strip_tags(stripslashes($_POST['prf-title']));
		$options['customtext'] = strip_tags(stripslashes($_POST['prf-custom']));
		$options['feedurl'] = strip_tags(stripslashes($_POST['prf-feedUrl']));
		$options['feedreaders'] = $_POST['checked'];
		update_option('prf_widget', $options);
	}
	
	$title = htmlspecialchars($options['title'], ENT_QUOTES);
	$customtext = htmlspecialchars($options['customtext'], ENT_QUOTES);
	$feedurl = ($options['feedurl']=="") ? get_bloginfo('rss_url') : $options['feedurl'];
	$selreaders = $options['feedreaders'];
	if(!is_array($selreaders)) {
		$selreaders[]=1;
	}
	
	print '<p style="text-align:left;"><label for="prf-title">' . __('Title:') . ' <input style="width: 200px;" id="prf-title" name="prf-title" type="text" value="'.$title.'" /></label></p>';
	?>
<table class="form-table">
  <tr>
    <td width="75" valign="top"><strong>Your blog's RSS feed:</strong></td>
    <td><input type="text" value="<?php print $feedurl; ?>" class="regular-text" name="prf-feedUrl" id="prf-feedUrl"/>
      <br />
      <?php _e('This is your blog\'s RSS feed, if you are using a custom feed (such as Feedburner) you can change the blog feed you wish to promote with this widget.'); ?> </td>
  </tr>
</table>
<br />
<table class="widefat" cellspacing="0">
<thead>
  <tr>
    <th scope="col" class="manage-column check-column"><input type="checkbox" /></th>
    <th scope="col" colspan="5"><?php _e('Select which feed readers you would like to feature:'); ?></th>
  </tr>
</thead>
<tfoot>
  <tr>
    <th scope="col" class="manage-column check-column"><input type="checkbox" /></th>
    <th scope="col" colspan="5"><?php _e('Select which feed readers you would like to feature:'); ?></th>
  </tr>
</tfoot>
<tbody>
  <?php
	//the user selection of feed readers
	$i=0;
	$selector = in_array('feedicon',$selreaders) ? "checked=checked" : "";
	print "<tr><th scope='row' class='check-column'><input type='checkbox' name='checked[]' value='feedicon' ".$selector." /></th>";
	print "<td colspan=5><img src=\"".WP_PLUGIN_PATH."/images/feedicon.gif\"> <input style=\"width: 200px;\" id=\"prf-custom\" name=\"prf-custom\" type=\"text\" value=\"".$customtext."\" /><br>Edit the text above for a direct link to subscribe to the feed.</td></tr>";
	foreach($feedreaders as $fr) {
		if($i%3==0) { print "<tr>"; }
		$selector = in_array(key($feedreaders),$selreaders) ? "checked=checked" : "";
		print "<th scope='row' class='check-column'><input type='checkbox' name='checked[]' value='".key($feedreaders)."' ".$selector." /></th>";
		print "<td><img src=\"".WP_PLUGIN_PATH."/images/".$fr[1]."\"></td>";
		if($i%3==2) { print "</tr>"; }
		$i++;
	}
	print "</tbody></table>";
	print "<div align=right><small>Promote RSS Feed Widget is created by <a href=http://www.anthonymontalbano.com target=_blank>Anthony Montalbano</a> &copy; 2009</small></div>";
	print '<input type="hidden" id="psfw-submit" name="psfw-submit" value="1" />';
  }
	
	//register widget to Wordpress
	register_sidebar_widget(array('Promote RSS Feed', 'widgets'), 'prf_widget_display');
	register_widget_control(array('Promote RSS Feed', 'widgets'), 'prf_widget_admin', 450, 200);
}

?>
