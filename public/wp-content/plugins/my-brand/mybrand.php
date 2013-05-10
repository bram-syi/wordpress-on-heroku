<?php
/*
Plugin Name: My Brand Login
Plugin URI:  http://mybrand.gdsweb.ca
Description: Customize Your Login, Registration, Lost Password and SOME Features of the Admin Panel. Visit the <a href="http://mybrand.gdsweb.ca" target="_blank">My Brand Login</a> Site For More Info, or <a href="http://forum.gdsweb.ca" target="_blank">Here</a> For Support.
Author: Kris Jonasson
Author URI:  http://krisjaydesigns.com
Version: 1.6.5

    My Brand Login Copyright (c) Kris Jonasson, Winnipeg, MB, Canada.
    All Code is Free To Modify For Your Own Use Unless Stated in Script.

    I Thank Horacio Figarella For Contributing To The Plugins Development
    By Modifying The Code To Work On Install URIs Outside The Default WP.
    http://www.1ero.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License; Version 3 Only;
    As published by the Free Software Foundation;

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU 3 General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

    Edit At Your Own Risk. I Don't Provide Modification Fixes For Free. I Have Outlined The Various Functions Throughout The
    Code For Help and Reference. If A Function Is Not Working, Contact Me For A Fix. Visit http://forum.gdsweb.ca for support. 
    I DO NOT provide support from the WordPress.Org website. Provide Your Current WP Version, Include Reference to Other 
    Plugins You're Using and What Version of MBL You Have Installed When Posting For Support. Please Be Patient When Waiting 
    For A Response (12-48 Hours). I'm Only One Person, And My Paid Projects Come Before My Free Services and Free Plugin Support. 
*/ ?>
<?php include('loader/templates/actions.php'); ?>
<?php include('loader/templates/functions.php'); ?>
<?php  function my_brand_page() { ?>
        <script type='text/javascript' src='<?php echo plugins_url('my-brand/loader/js/mybrandlogin.js'); ?>'></script>
           <script type="text/javascript">
              <!--//--><![CDATA[//><!--
                       document.write( '<div id="mybrandlogin">' );
                     //--><!]]>
               </script>
<?php } /* Begin My Brand Login Options Page. Edit At Own Risk */
   function login_brand() { ?>
    <link rel="stylesheet" href="<?php echo plugins_url('my-brand/loader/templates/css/mybrandADD.css'); ?>" type="text/css" />
  <div class="wrap">
<?php include('loader/templates/optionsjscss.php'); ?>
       <link rel="stylesheet" href="<?php echo plugins_url('my-brand/loader/templates/css/pop.css'); ?>" type="text/css" />
<h2><?php _e('My Brand Login Options'); ?></h2>	
  <div id="mybrand">
<?php include('loader/templates/optionsmenu.php');
            include('loader/templates/optionsmain.php'); ?>
<?php include('loader/templates/optionsright.php');
            include('loader/templates/optionsfoot.php'); ?>
       </div>
    </div>
<?php } /* End My Brand Login Script *//* My Brand Login 1.6.5 Copyright (c) 2009-2011 Kris Jonasson - www.krisjaydesigns.com - www.gdsweb.ca */ ?>