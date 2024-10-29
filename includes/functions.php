<?php

function initialize()
{	    
  //does not relate to actuall plugin version number
    $currentVersion = "42";
	if (get_option('ATVersion','') != $currentVersion && $_GET["updated"] != 1)
	{
		update_option('ATverifier', '');						
		update_option('ATtoken', '');				
		add_action('admin_head', 'account_reauthorize'); // called after the redirect
		do_action('admin-head');				
	}
	
	else 
	{
		remove_action('admin_head', 'account_reauthorize');
		add_action('admin_head', 'hide_reauthorize'); // called after the redirect
		do_action('admin-head');
		update_option('ATVersion', $currentVersion);		
	}
	
}








//runs when you publish a new post from new or from draft
function tweet_post($post)  {    
	
	$post_ID = $post->ID;	
	
	//prevents tweets to updates
	if( $_POST['post_status'] == 'publish' && $_POST['original_post_status'] != 'publish' )
	{	
			 global $wpdb;
			 $sql = "SELECT post_title FROM $wpdb->posts WHERE ID = $post_ID;";
			 $post_title = $wpdb->get_var($wpdb->prepare($sql));
			 $post_title .= ' ';
			 
			 $short_url = shorten_url(get_permalink($post_ID));
			 
			 if (strlen($post_title) + strlen($short_url) > 140)
			 {
				$total_len = strlen($post_title) + strlen($short_url);
				$over_flow_count = $total_len - 140;
				$post_title = substr($post_title,0,strlen($post_title) - $over_flow_count - 3);
				$post_title .= '...';		
			 }
			 
			 $message = $post_title . $short_url;
			 tweet($message);

			return $post_ID;
	
	}
}










function tweet_future_post($post)  {  	

			 $post_ID = $post->ID;
			 global $wpdb;
			 $sql = "SELECT post_title FROM $wpdb->posts WHERE ID = $post_ID;";
			 $post_title = $wpdb->get_var($wpdb->prepare($sql));
			 $post_title .= ' ';
			 
			 $short_url = shorten_url(get_permalink($post_ID));
			 
			 if (strlen($post_title) + strlen($short_url) > 140)
			 {
				$total_len = strlen($post_title) + strlen($short_url);
				$over_flow_count = $total_len - 140;
				$post_title = substr($post_title,0,strlen($post_title) - $over_flow_count - 3);
				$post_title .= '...';		
			 }
			 
			 $message = $post_title . $short_url;
			 tweet($message);

			return $post_ID;	
}











function shorten_url($long_url)
{
	try {		
	
			//autotwitter fun.ly API Credentials ( contact us for your own :) 
			$username = 'admin';
			$password = '123abc';
									
			$format = 'json';				
			$usercode = get_option('ATusercode','');	
			
			if (!$usercode > 10000001)
			{
				$usercode = -10000000;
			}
					
			
			$api_url = 'http://fun.ly/yourls-api.php';	
			$params = array(     // Data to POST
								'url'      => $long_url,					
								'format'   => $format,
								'action'   => 'shorturl',
								'username' => $username,
								'password' => $password,
								'usercode' => $usercode
							);			
			
			$response_data = curl_autotwitter($api_url,$params);
			
			$obj = json_decode($response_data);
			
			$short_url = $obj->{'shorturl'};
			
			return $short_url;
	
	}
	catch (Exception $e)
	{
	  		return "";	
	}
}

  










function tweet($msg)
{
	$url = 'http://twitter.com/statuses/update.json';	
	
	if ($connection = funly_oauth_connection())
	{
		$connection = funly_oauth_connection();		
		$results = $connection->post($url, 
						  			array(
										'status' => $msg,
										'source' => 'autotwitter'
									));	
		
		update_option('ATautotweets', get_option('ATautotweets','0')+1);
	}
}	









//specific code for funly application
function funly_oauth_connection() 
{	
	
	$oauth_token = get_option('ATtoken','');
	$oauth_secret = get_option('ATverifier','');	
	
	$twitter_app = get_twitter_app();
	
	$consumerKey = $twitter_app->{'api'};
	$appkey = $twitter_app->{'key'};	
	
	if(get_option('ATappkey','') != $appkey)
	{
		add_action('admin_head', 'account_reauthorize'); // called after the redirect
		do_action('admin-head');
		update_option('ATappkey', $appkey);		
	}
	
/*	echo $consumerKey;
	echo "<br>";
	echo $appkey;
	echo "<br>";
	echo $oauth_token;
	echo "<br>";
	echo $oauth_secret;
	echo "<br>";
	
	exit;*/
	
	if ( !empty($consumerKey) && !empty($appkey) && !empty($oauth_token) && !empty($oauth_secret) ) 
	{		
		require_once('twitteroauth.php');
		$connection = new TwitterOAuth($consumerKey, $appkey, $oauth_token, $oauth_secret);
		$connection->useragent = 'Auto Twitter - http://cash.fun.ly';		
		return $connection;		
	}
	else 
	{		
		return false;
	}
}
  
  
  
  
  



  
  


function get_twitter_app()
{
	$api_url = 'http://fun.ly/update/index.php';	
	$params = array("null"=>"null");			
			
	$response_data = curl_autotwitter($api_url,$params);						
	
	$obj = json_decode($response_data);
	
	return $obj;
	
}












  
function funly_import_accounts()
{
	try 
	{		
			$username = $_POST["funly_username"];
			$password = $_POST["funly_password"];	
			
			$authToken = funly_authorize($username, $password);
			$authorized = $authToken -> {'Authorized'};
		
			if($authorized == 1)
			{
				$accounts = funly_getAccounts($authToken);		
				$account_data = $accounts -> {'_empty_'};		
				
				$cnt = 0;
				foreach($account_data as $row)
				{		
					$cnt++;
					update_option('fc_id'.$cnt, $row->{'id'});	 
					update_option('fc_account_name'.$cnt, $row->{'account_name'});
					update_option('fc_type'.$cnt, $row->{'type'});	 
					update_option('fc_user_id'.$cnt, $row->{'user_id'});	 
					update_option('fc_access_token'.$cnt, $row->{'access_token'});	 
					update_option('fc_account_id'.$cnt, $row->{'account_id'});	 
					update_option('fc_profile_pic'.$cnt, $row->{'profile_pic'});	 
					update_option('fc_account_profile_url'.$cnt, $row->{'account_profile_url'});	 
					update_option('fc_profile_pic'.$cnt, $row->{'profile_pic'});	
					update_option('fc_enabled'.$cnt, $row->{'enabled'});	 		
/*
					echo'fc_id'.$cnt. $row->{'id'}." "." ";	 
					echo'fc_account_name'.$cnt. $row->{'account_name'}." ";
					echo'fc_type'.$cnt. $row->{'type'}." ";	 
					echo'fc_user_id'.$cnt. $row->{'user_id'}." ";	 
					echo'fc_access_token'.$cnt. $row->{'access_token'}." ";	 
					echo'fc_account_id'.$cnt. $row->{'account_id'}." ";	 
					echo'fc_profile_pic'.$cnt. $row->{'profile_pic'}." ";	 
					echo'fc_account_profile_url'.$cnt. $row->{'account_profile_url'}." ";	 
					echo'fc_profile_pic'.$cnt. $row->{'profile_pic'}." ";	
					echo'fc_enabled'.$cnt. $row->{'enabled'}." ";	*/			
					
				}
				
				update_option('fc_account_count',$cnt);
				
				if($cnt > 0)
					return html_import_success();
				else 
					return html_import_no_accounts();
					
			}
			else 
			{
				return html_import_auth_failed();	
			}
	}
	catch (Exception $e)
	{
		return html_import_error();
	}
		
}










function funly_authorize($username, $password)
{	
	
	$api_url = 'http://cash.fun.ly/api/authenticate.aspx';	
	$params = array(     // Data to POST
						'username'   => $username,					
						'password'   => $password
				    );			
			
	$response_data = curl_autotwitter($api_url,$params);						
	
	$obj = json_decode($response_data);
	
	$authToken = $obj;
	
	return $authToken;	
	
}










function funly_getAccounts($authToken)
{		
	$api_url = 'http://cash.fun.ly/api/getAccounts.aspx';	
	
	$params = array( 'authToken'   => json_encode($authToken) );			
			
	$response_data = curl_autotwitter($api_url,$params);

	$obj = json_decode($response_data);
	
	$accounts = $obj;
	
	return $accounts;		
}














function deauthorize()
{
	update_option('ATverifier', '');						
	update_option('ATtoken', '');						
	add_action('admin_notices', 'account_deauthorized');			
	do_action('admin_notices');
}










function curl_autotwitter($url, $params)
{
		// Init the CURL session		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result
		curl_setopt($ch, CURLOPT_POST, 1);              // This is a POST request
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	
		// Fetch and return content
		$data = curl_exec($ch);
		curl_close($ch);
		
		return $data;	
}












//function sets any setting variables that were posted or are on the query string
//returns 1 or 0 whether it updated anything in order to show a message
function update_options($updated)
{
	
	if (isset($_POST['ATdisplay'])) {
		update_option('ATdisplay', $_POST['ATdisplay']);
		$updated = 1;
	}
	
	if (isset($_POST['ATretweet'])) {
		update_option('ATretweet', $_POST['ATretweet']);	
		$updated = 1;
	}
	
	if (isset($_GET['token'])) {
		update_option('ATtoken', $_GET['token']);						
		$updated = 1;		
	} 	
	
	if (isset($_GET['verifier'])) {
		update_option('ATverifier', $_GET['verifier']);						
		$updated = 1;		
	} 
	
	if (isset($_GET['appkey'])) {
		update_option('ATappkey', $_GET['appkey']);						
		$updated = 1;	
	}
	
	if (isset($_GET['screenname'])) {
		update_option('ATscreenname', $_GET['screenname']);						
		$updated = 1;
	}
	
	if (isset($_POST['usercode'])) {
		update_option('ATusercode', $_POST['usercode']);				
		$updated = 1;
	}
	
	if (isset($_GET['avatar'])) {
		update_option('ATavatar', $_GET['avatar']);				
		$updated = 1;
	}

	return $updated;
}









function updated_message() 
{
  echo '<center><div class="updated" style="width:660px;"><p>Settings saved</p></div></center>';
}  


function authorized_message() 
{
  echo '<div class="updated" style="width:660px;"><center><p>Successfully connected to Twitter!</p></center></div>';
}

function account_deauthorized() 
{
  echo '<div class="updated" style="width:660px;"><center><p>This application has been disconnected from your Twitter account</p></center></div>';
}
 

function account_reauthorize() 
{
  echo "<div class='update-nag'><center><p>Auto Twitter needs you to <a href='options-general.php?page=Auto-Twitter-Options'>authorize your witter account.</a></p></center		></div>";
}

function hide_reauthorize() 
{
   echo '';
}






