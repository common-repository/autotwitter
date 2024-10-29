<?php
/*
Plugin Name: Auto Twitter
Plugin URI: http://fun.ly/plugin
Description: Auto Twitter Poster
Version: 1.62
Author: Unreal Media
Author URI: http://www.unrealmediallc.com
Programmer Email: robborden gmail.com
License: GPL2
*/

require_once("includes/pluginPage.php");
require_once('includes/functions.php');
require_once('includes/twitteroauth.php');

// add_action (action that you want to hook into, function you want to run)
add_action( 'init', 'initialize' );
add_action('admin_menu', 'autoTwitter_admin_menu');
add_action('new_to_publish', 'tweet_post', 10, 1);
add_action('draft_to_publish', 'tweet_post', 10, 1);
add_action('pending_to_publish', 'tweet_future_post', 10, 1);
add_action('future_to_publish', 'tweet_future_post', 10, 1);

if (get_option('ATretweet','no') == 'yes') 
					add_action('publish_to_publish', 'tweet_future_post', 10, 1);

function autoTwitter_admin_menu() 
{ 
  $pluginPage = add_options_page('Auto Twitter Options', 'Auto Twitter', 'manage_options', 'Auto-Twitter-Options', 'plugin_options');   
}		
