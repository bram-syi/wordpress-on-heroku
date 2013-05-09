<?php
define('XMLRPC_REQUEST', false);
require('../wp-blog-header.php');

wp_die('you don\'t need to be here!');

remove_all_actions('wp_head');
ensure_logged_in_admin();
?>
<html>
  <head>
    <title>editor</title>
    <script type="text/javascript" src="jquery-ui/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="jquery-ui/js/jquery-ui-1.8.20.custom.min.js"></script>
    <link type="text/css" href="jquery-ui/css/ui-lightness/jquery-ui-1.8.20.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="jquery.url.js"></script>
    <script type="text/javascript" src="jquery.qtip-1.0.0-rc3.min.js"></script>
    <script type="text/javascript" src="editor.js"></script>
  </head>
  <body>
    <style>
#editing_panel {
  float:left;
  width:25%;
}
#preview_theme, #preview_start {
  float:left;
  width:70%;
}
iframe {
  width:1024px;
  height:100%;
}
label {
  display:block;
}
input, textarea {
  margin-bottom:2ex;
}
div {
  padding-left:2ex;
}
.fakelink {
  text-decoration:underline;
  color:blue;
  cursor:pointer;
  font-size:70%;
}
.not_impl {
  font-size:80%;
}
    </style>
  </head>
  <body>
    <div id="editing_panel">
      <h3>theme info</h3>
      <div>
        <label for="theme_id" title="ID of the theme">Theme ID</label>
        <input type="text" id="theme_id"/>
        <div id="status">
        </div>
      </div>
      <div>
        <input type="submit" id="save_theme" value="Save this theme" disabled="disabled"/>
      </div>
      <h3>start page <span class="fakelink"  id="show_start">show</span></h3>
      <div id="start_page_area">
        <!--
        <div>
          <label for="start_banner" title="Banner/header image at the top of the Start page">Starting Banner</label>
          <textarea id="start_banner" class="settable"/></textarea>
        </div>
        <div>
          <label for="start_label" title="Heading that will appear under the 'Starting Banner'">Starting Label</label>
          <input type="text" id="start_label" class="settable"/>
        </div>
        <div>
          <label for="body_label" title="Text on start page that asks why user is fundraising">Personal Label</label>
          <input type="text" id="body_label" class="settable"/>
        </div>
        -->
        <div>
          <label for="post_title" title="Title of the HTML page">B. Fundraiser Headline</label>
          <input type="text" id="post_title" class="settable"/>
        </div>
        <div>
          <label for="post_content" title="Text for the user's reason for fundraising">C. Fundraiser Copy</label>
          <textarea id="post_content" class="settable"/></textarea>
        </div>
        <div>
          <label for="goal" title="Setting this to non-zero will turn on the bar that fills up towards the goal amount">Goal</label>
          <input type="text" id="goal" class="settable"/>
        </div>
        <div>
          <label for="org" title="The organization that this theme belongs to (eg, 'pratham' or 'kidsco')">Organization Name</label>
          <input type="text" id="org" class="settable"/>
        </div>
        <div>
          <label for="required_fields" title="The list of elements that are shown (to be edited by the individual fundraiser)">Show the following:</label>
          <ul id="required_fields">
            <li><input type="checkbox" class="settable" name="post_title"/><span>B. Fundraiser Headline</span></li>
            <li><input type="checkbox" class="settable" name="post_content"/><span>C. Fundraiser Copy</span></li>
            <li><input type="checkbox" class="settable" name="goal"/><span>Goal</span></li>
            <li><input type="checkbox" class="settable" name="team"/><span>Team Picker</span></li>
          </ul>
        </div>
        <div>
          <label for="tag" title="Tag to identify the proper gifts to display, ONLY AFFECTS FUTURE FUNDRAISERS, NO EFFECT ON EXISTING FUNDRAISERS">Gift Tag(s)</label>
          <input type="text" id="tag" class="settable"/>
        </div>
      </div>
      <hr/>
      <h3>fundraiser page <span class="fakelink" id="show_theme">show</span></h3>
      <div id="fundraiser_page_area">
        <!--
        <div>
          <label for="banner" title="Banner/header image at the top of the Fundraiser page">Fundraiser Banner</label>
          <textarea id="banner" class="settable"></textarea>
        </div>
        <div>
          <label for="title" title="Heading that will appear under the 'Fundraiser Banner'">Fundraiser Label</label>
          <input type="text" id="title" class="settable"/>
        </div>
        <div>
          <label for="about" title="HTML description of the campaign itself">Campaign Promo</label>
          <textarea id="about" class="settable"></textarea>
        </div>
        <div>
          <label for="gifts_content" title="Text that appears next to the gift links">Gift Label</label>
          <textarea id="gifts_content" class="settable"></textarea>
        </div>
        <div>
          <label for="about" title="General info about the compaign, the same for all fundraisers">Campaign Info</label>
          <textarea id="about" class="settable"></textarea>
        </div>
        -->
        <div>
          <label for="heading" title="Orange header title at the top of the fundraiser page">B. Fundraiser Headline <b>override</b></label>
          <input type="text" id="heading" class="settable" />
        </div>
        <div>
          <label for="comments" title="Heading that appears above the Facebook comments (&quot;[name]&quot; and &quot;love&quot; will be auto-replaced)">Comment Label</label>
          <input type="text" id="comments" class="settable"/>
        </div>
        <div>
          <label for="gifts" title="Display the gift-picking widget">K. Gift Widget</label>
          <input type="checkbox" id="gifts" class="settable"/>
        </div>
        <div>
          <label for="gifts_header" title="Header that appears above the gift browser">Gift browser header</label>
          <input type="text" id="gifts_header" class="settable" />
        </div>
        <div>
          <label for="show_private" title="Display gifts on organizations that are flagged 'preview'">Show private gifts</label>
          <input type="checkbox" id="show_private" class="settable" />
        </div>
        <div>
          <label for="fields" title="Editable items">Allow editing:</label>
          <ul id="fields">
            <li><input type="checkbox" class="settable" name="post_title"/><span>B. Fundraiser Headline</span></li>
            <li><input type="checkbox" class="settable" name="post_content"/><span>C. Fundraiser Copy</span></li>
            <li><input type="checkbox" class="settable" name="goal"/><span>Goal</span></li>
            <li><input type="checkbox" class="settable" name="photo"/><span>Photo</span></li>
            <li><input type="checkbox" class="settable" name="team"/><span>Team Picker</span></li>
          </ul>
        </div>
        <div>
          <label for="show_admins_last_names" title="Show last names of donors to admins when they view this fundraiser">Show last names to admins</label>
          <input type="checkbox" id="show_admins_last_names" class="settable" />
        </div>
        <div>
          <label for="downplay_money" title="This will hide the current raised amount from the progress bar, and from the donation activity list. However, the fundraiser owner will always see these numbers.">Downplay money</label>
          <input type="checkbox" id="downplay_money" class="settable" />
        </div>
      </div>
      <h3>email settings</h3>
      <div id="email_settings_area">
        <div>
          <label for="h20_default_invite_message" title="When the 'invite' button is pressed, this is the message that will appear by default">Default Invite Message</label>
          <textarea id="h20_default_invite_message" class="settable"></textarea>
        </div>
      </div>
      <h3>campaign creation</h3>
      <div id="campaign_creation_area">
        <div>
          <label for="cc_teams" title="List of teams to create for the new campaign, one per line">Teams (optional)</label>
          <textarea id="cc_teams" rows="5"></textarea>
        </div>
        <div>
          <div id="cc_status"></div>
          <input type="submit" id="cc_submit" value="Create this theme"/>
        </div>
      </div>
      <h3>facebook settings</h3>
      <div id="facebook_settings_area">
        <div>
          <label for="facebook_default_donation_message" title="This is displayed in a post on the donor's Facebook feed when they make a donation">Donation Message</label>
          <input type="text" id="facebook_default_donation_message" class="settable"></input>
        </div>
      </div>
    </div>
    <div id="preview_theme">
      <iframe scrolling="yes" src=""></iframe>
    </div>
    <div id="preview_start" style="display:none">
      <iframe scrolling="yes" src=""></iframe>
    </div>
    <div id="tbd" style="display:none">
      <p class="not_impl">Not editable via this page yet, use the promo editor instead (the gray "Edit" box).</p>
    </div>
  </body>
</html>
