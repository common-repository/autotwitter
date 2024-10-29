<?php

function html_logo_and_form()
{
  $action = $_SERVER["REQUEST_URI"];
  $action = str_replace("&deauthorize=1","&deauthorize=0",$action);
  
  return "<div style='width:710px;margin-top:25px;margin-left:20px;'>		   
			<form id='autoTwitterForm' method='post' action='$action'>				
			    <div style='width:100%; background-color:white; border:1px solid #4CA3CC;'>			
					<a href='http://fun.ly'><img src='http://fun.ly/ATlogo.png' alt='Auto Twitter' border='0' /></a>						
				</div>		
	    ";
	
}

function html_directions_autotwitter()
{	
		return	"Authorize your Twitter account by clicking the red box below. 
				Auto Twitter will automatically publish your blog posts to all enabled accounts with a title and link.<br><br><br>";	
}

function html_directions_import()
{	
		return	"";	
}

function html_directions_import_auth()
{	
		return	"Enter your Fun.ly Cash Username and Password to import your Fun.ly Cash accounts.<br><br>
				<span style='font-size:15px;font-weight:bold;'>ALL EXISTING IMPORTED FUN.LY CASH ACCOUNTS WILL BE OVERWRITTEN</span><br><br>";	
}

function html_funly_cash_enrolled($usercode)
{	
	return "				
		<br><b>Fun.ly Cash - Earn Cash for your Clicks</b><br>
		<div style='border:1px solid #4CA3CC;width:700px;padding:5px;background-color:#FFFFE0;'>
		<center>Usercode: <input type='text' name='usercode' id='usercode_txt' maxlength='8' value='$usercode' style='width:78px; background-color:#EFEFEF;'						    disabled='true'/> 
		 <input type='button' id='edit_save' value='Edit' onclick=\"
		 			
 			var usercode_box = document.getElementById('usercode_txt');
			var edit_save_button = document.getElementById('edit_save');
			
			if(edit_save_button.value == 'Edit')
			{	
				usercode_box.style.backgroundColor = 'white';									
				usercode_box.disabled = false;
				edit_save_button.value = 'Save';
			}
			else
			{										
				this.form.submit();
			}\"
			/> </center></td></tr></table></div><br><br>";
}






function html_funly_cash_unenrolled($usercode)
{
	$usercode = "";
	return	"
		<br><b>Fun.ly Cash - Earn Cash for your Clicks</b><br>				
		<div style='border:1px solid #4CA3CC;width:700px;padding:5px;background-color:#FFFFE0;' >
		<table><tr><td valign='top' style='padding-left:20px; padding-top:12px; width:220px;'>
		<span style='color:#DB4E48'>Usercode:</span> 
		<input type='text' name='usercode' maxlength='8' value='$usercode' 
		style='width:78px;border:1px solid #DB4E48;background-color:#FFEBE8;' /> 	
		<input type='submit' name='sumbit' value='Save'>
		</td><td valign='top' style='padding-left:87px;'>
					
		<div style='border:1px solid #4CA3CC; cursor:pointer; width:380px; height:48px; text-align:center; background-color:#FFFFFF;' 
																				onclick=\"window.open('http://fun.ly/cash','_blank');\">
					<div style='float:left;'><img src='../wp-content/plugins/autotwitter/images/fc.png' alt='Auto Twitter' border='0' /></div>
					
		<a href='http://fun.ly/cash' style='font-size:10px;' target='_blank'>
		Fun.ly Cash Beta now open - Earn Cash for your Clicks <br>Sign Up Free! </a></div></td></tr></table></div><br><br>";	
}







function html_accounts_authorized($ATavatar, $screenname, $ATautotweets)
{
	$action = $_SERVER["REQUEST_URI"];
	$html =
	"<b>Accounts</b>
	<div style='width:700px; border:1px solid #4CA3CC; background-color:#FFFFE0; padding:5px;'>
	<table>
		<tr>
			<td>
				<table>
					<tr>						
						<td valign='top' width='55'>";						
							if ($ATavatar != '') $html .= "<img src='$ATavatar'> ";						
			  $html .= "</td>
							<td  valign='top' width='258'>
								<strong> $screenname</strong><br><span style='font-size:10px;'>Autotweets: $ATautotweets</span>
							</td>
							<td valign='middle' style='padding-left:13px;'>							
		    						<a href='options-general.php?page=Auto-Twitter-Options&deauthorize=1' style='font-size:10px;'>Deauthorize Account</a>
		    						<span style='font-size:10px; margin-left:45px;'>Having problems?  &nbsp; </span>	<a href='http://support.fun.ly' target='_blank' 
		    						style='font-size:10px;'>Get Support</a>
		    				</td>
		    		</tr>
		    	</table>
		    </td>
		</tr>
	</table>
			
	</div>
	<!--<div style='float:right;'><a href='options-general.php?page=Auto-Twitter-Options&mode=import_auth'>Import Fun.ly Cash Accounts</a></div>--><br><br>						    ";	
	
	return $html;

}







function html_accounts_not_authorized()
{	
		
		return "<b>Authorize</b>
				<div class='error' style='margin-left:0px; margin-top:-1px; width:682px; height:30px; padding-top:10px; padding-left:20px;'>
				<center>
					<a href='http://fun.ly/auth/redirect.php'>Authorize Twitter account</a> 
					<span style='font-size:10px; margin-left:45px;'>Having problems?  &nbsp; </span>					
					<a href='http://support.fun.ly' target='_blank' style='font-size:10px;'>Get Support</a>					
				</center>
				</div>
				<!--<div style='float:right;'><a href='options-general.php?page=Auto-Twitter-Options&mode=import_auth'>Import Fun.ly Cash Accounts</a></div>--><br/><br/>";
}







function html_options($ATretweet)
{
	
	
	$html = "<b>Options</b><div style='width:700px; border:1px solid #4CA3CC; background-color:#FFFFE0; padding:5px;'>
					<table><tr><td>Re-Tweet updated posts? </td>
				 <td> &nbsp; Yes <input type='radio' id='ATretweetYES'  name='ATretweet' value='yes' ";
			
	if ($ATretweet != 'no')	$html .= " checked='true' ";						
	$html .= "/>&nbsp; No <input type='radio' id='ATretweetNO' value='no' name='ATretweet' ";
			
	if ($ATretweet == 'no')	$html .= " checked='true' ";				
	$html .= " /></td></tr></table></div> ";
	
	return $html;
	
	
}






function html_save_donate()
{
	
	return	"<br>
		<table><tr><td valign='middle' width='200'>				
		<input type='submit' name='sumbit' value='Save Changes'>
		</td><td align='right' valign='top' style='padding-left:13px;width:500px;'>				
		<div style='float:right;'>
			<a href='https://www.paypal.com/cgi-bin/webscr?
					cmd=_donations&business=funlycash%40gmail%2ecom&lc=US&item_name=Auto%20Twitter%20donation&
					no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHostedGuest' target='_blank' />
					<img src='../wp-content/plugins/autotwitter/images/btn_donate_LG.gif' alt='Donate to the developer'></a>
		</div>
		</td></tr></table>			
		
		</form>
 		</div><br><br>";
	
}







function html_blog()
{	
				
	return "<span style='margin-left:20px;'><b>Blog</b></span>		
			
	<div style='width:710px; height:180px; background-color:#FFFFE0; margin-left:20px; border:1px solid #4CA3CC;'>
					<iframe src='http://cash.fun.ly/iFrames/ATblog.aspx' scrolling='no' frameborder='0' width='700'></iframe>
			</div>		
			
			<div style='width:710px; margin-left:20px;'>			
				<span style='font-size:12px;float:right;'>For more news and updates 
				<a href='http://blog.fun.ly' style='font-size:12px;' target='_blank'>check out our blog</a></span>
			</div>
			
			<br><br><br>";
}


///////////////  Import UI ///////////////////////////////////////////////////////////////////////////////////////////////////////

function html_import_login()
{
	
	return "<div style='margin-bottom:200px;'>
			<table>
				<tr>
					<td>Username</td>
					<td><input type='text' name='funly_username' /></td>
				</tr>
				<tr>
					<td>Password</td>
					<td>
						<input type='password' name='funly_password'> 
						<input type='button' name='login' value='Import' 
						onclick='this.form.action = \"options-general.php?page=Auto-Twitter-Options&mode=import\";this.form.submit();' />
					</td>
				</tr>
			</table>
			</div>";
	
}


function html_import_no_accounts()
{
	return "<br><br><br><center>No accounts available to import.  <a href='options-general.php?page=Auto-Twitter-Options&mode=import_auth'>Try again?</a>.
			<br><br><br><a href='options-general.php?page=Auto-Twitter-Options'>Return to Auto Twitter Settings</a></center>";	
}

function html_import_error()
{	
	return "<br><br><br><center>An error occured while importing.  
			<a href='options-general.php?page=Auto-Twitter-Options&mode=import_auth'>Please try again</a>.
			<br><br><br><a href='options-general.php?page=Auto-Twitter-Options'>Return to Auto Twitter Settings</a></center>";	
}

function html_import_auth_failed()
{	
	return "<br><br><br><center>Login attempt failed.  <a href='options-general.php?page=Auto-Twitter-Options&mode=import_auth'>Please try again</a>.
			<br><br><br><a href='options-general.php?page=Auto-Twitter-Options'>Return to Auto Twitter Settings</a></center>";
}

function html_import_success()
{
	return "<br><br><br><center>Success! The following accounts where imported: <br><br>" . html_display_accounts() . 
			"<br><br><a href='options-general.php?page=Auto-Twitter-Options'>Return to Auto Twitter Settings</a></center>";
}

function html_display_accounts()
{
	$cnt = get_option('fc_account_count','0');
	$account_string = "<table>";
	
	$j=0;
	for($i==0; $i<$cnt; $i++)
	{
		$j = $i+1;
		$type = get_option('fc_type'.$j,'0');
		$account_name = get_option('fc_account_name'.$j,'0');
		
        switch ($type)
        {
            case "Facebook":
                $accountProfileURL = "http://www.facebook.com/?uid=" . get_option('fc_account_id'.$j,'');
                $imageURL = urldecode("http://graph.facebook.com/" . get_option('fc_account_id'.$j,'') . "/picture");
                break;
            case "Twitter":
                $accountProfileURL = "http://www.twitter.com/" . get_option('fc_account_name'.$j,'');
                $imageURL = urldecode(get_option('fc_account_profile_url'.$j,''));
                break;
        }
        
        
        
        $account_string .= 
        "<tr><td width='60' valign='top'><img src='$imageURL' /></td> 
        <td valign='top' align='left'>$type - <a href='$accountProfileURL' target='_blank' >$account_name</a></td></tr>";
		
		
	}	
	
	$account_string .= "</table>";
	
	if ($j = 0)
			return html_import_no_accounts();
	else
			return $account_string;
			
			
}