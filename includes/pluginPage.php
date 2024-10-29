<?php

function plugin_options() {    
  
    if (!current_user_can('manage_options'))  {
	  wp_die( __('You do not have sufficient permissions to access this page.') );
    }		    
   
    //has all the UI html
    require_once("pluginPageHtml.php");
	
    //print the logo and the opening form tag
	echo html_logo_and_form();		
	
	//show import direction if the "mode" is set otherwise, normal directions
	if (!isset($_GET["mode"])) 
			echo html_directions_autotwitter(); 
	elseif($_GET["mode"]=="import_auth") 
			echo html_directions_import_auth();
	else 
			echo html_directions_import();
	
	
	//deauthorize the account (doesn't actually deauth on the twitter website yet
	if (isset($_GET['deauthorize']) && $_GET['deauthorize'] == 1 && !isset($_GET['verifier'])&& !isset($_GET['token'])) {
		deauthorize();
	}
	
	//we're going to see if any of the post variables that we save were set and update them
	$updated = 0;
	$updated = update_options($updated);		
	
	//signals that we came back from authorizing twitter
	$twitterAuth = 0;
	if (isset($_GET['updated'])) {		
		$updated = 1;
		$twitterAuth = 1;
	}

	//the message to show on postback			
	if (isset($_GET['updated'])) //this only comes in on the query string if its coming back from oAuth so authorized message
	{		
		add_action('admin_notices', 'authorized_message');			
		do_action('admin_notices');		
		remove_action('admin_head', 'account_reauthorize');				
		$updated = 0;			
	}
	elseif ($updated == 1) //this means a regular postback occured ie. settings saved
	{		
		add_action('admin_notices', 'updated_message');			
		do_action('admin_notices');
		$updated = 0;
	}	
	
	//getting settings for display on UI		
	$ATdisplay = get_option('ATdisplay','yes');
	$oauth_token = get_option('ATtoken','');
	$oauth_secret = get_option('ATverifier','');
	$screenname = get_option('ATscreenname','');
	$usercode = get_option('ATusercode','00000000');	
	$ATretweet = get_option('ATretweet','no');
	$ATavatar = get_option('ATavatar','');
	$ATautotweets = get_option('ATautotweets','0');
	$FC_accounts_count = get_option('fc_account_count','0');
	
	//we're going to generate the rest of the page html (and import fun.ly cash accounts if appropriate)
	$pageHTML = "";
	if (isset($_GET["mode"])) //this is used for importing
	{
		
			if ($_GET["mode"] == "import_auth")			
				$pageHTML .= html_import_login();
									
			elseif ($_GET["mode"] == "import")									
				$pageHTML = funly_import_accounts();				
			
			else				
				$pageHTML .= html_import_error();
			
	}
	else 
	{
		
			if ($usercode > 10000000)		
				$pageHTML .= html_funly_cash_enrolled($usercode);	
			else 	
				$pageHTML .= html_funly_cash_unenrolled($usercode);	
			 
		
		
			if(strlen($oauth_token) > 0 && strlen($oauth_secret)>0 )								
				$pageHTML .= html_accounts_authorized($ATavatar, $screenname, $ATautotweets);	
			else 	
				$pageHTML .= html_accounts_not_authorized();	
								
				
			
			$pageHTML .= html_options($ATretweet);								
			$pageHTML .= html_save_donate(); 		 
			$pageHTML .= html_blog();
			
	}	
	
	echo $pageHTML . "";	

}