<?php
	$ismailed=0;
	if (isset($_POST['email']) && $_POST['email']!='')
	   {
		  $name=$_REQUEST['cproject'];
		  $email = $_REQUEST['email'];
		  $source = $_REQUEST['source'];
		  $requirement = $_REQUEST['requirement'];
		  $ipaddress = $_SERVER['REMOTE_ADDR'];
		  $headers  = 'From: ' .$email. "\r\n" .
					'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
		  $subject="Contactus";
		  
		  $message ="<table border='1' cellpadding='20'>
			<tr><td>cproject:</td><td>$name</td></tr>
			<tr><td>Email:</td><td>$email</td></tr>
			<tr><td>Source:</td><td>$source</td></tr>
			<tr><td>IP Address:</td><td>$ipaddress</td></tr>
			<tr><td>Requirement:</td><td>$requirement</td></tr>
		  </table>";

		  $to = 'sivareddybtech@gmail.com';
		 // $to = 'admin@taggerzz.com';
		  $ismailed=mail($to,$subject,$message, $headers);
		}
	?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  <script src="js/menu.js"></script>  <!-- CSS -->
<link href="css/normalize.css" rel="stylesheet" type="text/css" />
<link href="css/blog.css" rel="stylesheet" type="text/css" />
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
<link href="css/media-queries.css" rel="stylesheet" type="text/css" /><!--CSS-->

<title>Help Topics for Taggerzz.com</title>
</head>

<body>
<div class="main_block">
<div class="header">
  <a href="index.htm"><img src="imgs/logo_new_red.png" class="logo"/></a> <h3 class="heading">Help Topics, Guidelines to Learn &amp; Feedback</h3>
<div class="menu_block">
  <div class="menu_holder"> <nav>
          <ul class="cf">
            <li class="active"><a href="taggerzz-landing-page.htm">TAGGERZZ.COM's LANDING PAGE</a>
              <ul>
                <li><a href="taggerzz-landing-page.htm#autologin">Auto Social Media Logins</a></li>
                <li><a href="taggerzz-landing-page.htm#totalactivity">Total Details of Activity</a></li>
                <li><a href="taggerzz-landing-page.htm#signup">Email ID & Password Signup</a></li>
              </ul>
            </li>
            <li class="active"><a href="taggerzz-header.htm">TAGGERZZ HEADER</a>
              <ul>
                <li><a href="taggerzz-header.htm#activityrpt">User Activity Report (Header)</a></li>
                <li><a href="taggerzz-header.htm#crtdhighlights">Number of User Created Highlights</a></li>
                <li><a href="taggerzz-header.htm#collectedresources">User Collected Web resources</a></li>
                <li><a href="taggerzz-header.htm#starttagging">Start #Tagging</a></li>
				<li><a href="taggerzz-header.htm#cashrewarding">Cash Rewarding Points</a></li>
				<li><a href="taggerzz-header.htm#convert">Convert (Conversion of Rewarding Points)</a></li>                  
              </ul>
            </li>
            <li class="active"><a href="taggerzz-home-content.htm">TAGGERZZ CONTENT</a>
              <ul>
                <li><a href="taggerzz-home-content.htm#highlightsshared">Highlights #tagged by Members</a></li>
                <li><a href="taggerzz-home-content.htm#publicdataboxes">Public Databoxes</a></li>
                <li><a href="taggerzz-home-content.htm#privatedataboxes">Private Databoxes</a></li>
                <li><a href="taggerzz-home-content.htm#members">Members</a></li>
              </ul>
            </li>
            
            <li class="active"><a href="taggerzz-creating-posting-highlight.htm">CREATING HIGHLIGHT</a>
              <ul>
				<li><a href="taggerzz-creating-posting-highlight.htm#starttagging">START #TAGGING</a></li>              
                <li><a href="taggerzz-creating-posting-highlight.htm#creatinghighlight">Creating your Highlight (Step/Page 1)</a></li>
                <li><a href="taggerzz-creating-posting-highlight.htm#uploadbookmarks">Upload Bookmarks (Step/Page 2)</a></li>
                <li><a href="taggerzz-creating-posting-highlight.htm#highlightfinalstep">Creating Highlight (Final Step/Page)</a></li>
              </ul>
            </li>
            <li class="active"><a href="taggerzz-creating-posting-public-databox.htm">CREATING PUBLIC DATABOX</a>
              <ul>
				<li><a href="taggerzz-creating-posting-public-databox.htm#starttagging">START #TAGGING</a></li>              
                <li><a href="taggerzz-creating-posting-public-databox.htm#creatingpublicdb">Creating your Public Databox (Step/Page 1)</a></li>
                <li><a href="taggerzz-creating-posting-public-databox.htm#uploadbookmarks">Upload Bookmarks (Step/Page 2)</a></li>
                <li><a href="taggerzz-creating-posting-public-databox.htm#finalpublicdatabox">Creating Public Databox (Final Step/Page)</a></li>
              </ul>
            </li>
            <li class="active"><a href="taggerzz-creating-posting-private-databox.htm">CREATING PRIVATE DATABOX</a>
              <ul>
				<li><a href="taggerzz-creating-posting-private-databox.htm#starttagging">START #TAGGING</a></li>              
                <li><a href="taggerzz-creating-posting-private-databox.htm#creatingprivatedb">Creating your Private Databox (Step/Page 1)</a></li>
                <li><a href="taggerzz-creating-posting-private-databox.htm#uploadbookmarks">Upload Bookmarks (Step/Page 2)</a></li>
                <li><a href="taggerzz-creating-posting-private-databox.htm#finalstepprvtdb">Creating Private Databox (Final Step/Page)</a></li>
              </ul>
            </li>
            
            <li class="active"><a class="dropdown" href="taggerzz-customization-mode.htm">CONTENT CUSTOMIZATION</a>
              <ul>
                <li><a href="taggerzz-customization-mode.htm#customizationmode">About Customization Mode</a></li>
                <li><a href="taggerzz-customization-mode.htm#addcomment">Add comment</a></li>
                <li><a href="taggerzz-customization-mode.htm#refreshlinks">Refresh Data</a></li>   
                <li><a href="taggerzz-customization-mode.htm#shareonsocialmedia">Share</a></li>                                
              </ul>
            </li>
            <li class="active"><a class="dropdown" href="taggerzz-montage-my-profile.htm">MONTAGE (MY PROFILE)</a>
              <ul>
                <li><a href="taggerzz-montage-my-profile.htm#settingprofile">Setting My Profile / Montage</a></li>
                <li><a href="taggerzz-montage-my-profile.htm#publicprivate">Public Databoxes</a></li>
                <li><a href="taggerzz-montage-my-profile.htm#publicprivate">Highlights</a></li>
              </ul>
            </li>
            <li class="active"><a class="dropdown" href="taggerzz-account-settings.htm">ACCOUNT SETTINGS</a>
              <ul>
                <li><a href="taggerzz-account-settings.htm#memberdetails">Member Details</a></li>
                <li><a href="taggerzz-account-settings.htm#changepassword">Change Password</a></li>                
                <li><a href="taggerzz-account-settings.htm#hideme">Hide me (in Members)</a></li>
                <li><a href="taggerzz-account-settings.htm#disableprsnlmessaging">Disable “Personal messaging”</a></li>                
                <li><a href="taggerzz-account-settings.htm#deactivateaccount">Deactivate Account</a></li>
				<li><a href="taggerzz-account-settings.htm#memberactivity">Member Activity Summary</a></li>                
              </ul>
            </li>
            <li class="active"><a class="dropdown" href="taggerzz-dashboard-personal-messages.htm">DASHBOARD</a>
              <ul>
                <li><a href="taggerzz-dashboard-personal-messages.htm#editcontent">Edit Highlights</a></li>              
                <li><a href="taggerzz-dashboard-personal-messages.htm#editcontent">Edit Public Databoxes</a></li>
                <li><a href="taggerzz-dashboard-personal-messages.htm#editcontent">Edit Private Databoxes</a></li>                
                <li><a href="taggerzz-dashboard-personal-messages.htm#editcontent">Hide me (in Members)</a></li>
                <li><a href="taggerzz-dashboard-personal-messages.htm#editcontent">Personal Messages</a></li>                
              </ul>
            </li>
            <li class="active"><a href="taggerzz-terms-and-conditions.htm">TERMS AND CONDITIONS</a></li>
            <li class="active"><a href="taggerzz-privacy-policy.htm">PRIVACY POLICY</a></li>            
            <li class="active"><a href="feedback-enquiry.php">REPORT ISSUE / CONTACT ADMIN</a></li>
        </ul>
        </nav></div>    
  </div>
</div>


</div>

<div class="cntnt_main">
<div id="passage_text">
<h2 class="heading_cnt">Contact Admin</h2>
<?php 
					if( $ismailed!=0){?>
					<script> 
						window.location = '/blog/feedback-enquiry.php?success=true';
					</script>
					<?php }if($_GET['success']){
						echo "<p  id='successMessage'><b>Thank you for contacting us, We will get back to you soon.</b></p> ";
					}
					?>
<div class="enquiry_block_new">
<form id="enquiryForm" name="enquiryForm" method="POST" onsubmit="contact();" >
<div class="enquiry_heading" id="enq_heading_text">Submit Your Enquiry/Feed back/Your Issue with any Feature</div>
      <select input name="cproject" id="cproject" type="text"  class="field_props" />
      
      <option value="">Please Select Your Request </option>
      <option value="Enquiry">Enquiry</option>
      <option value="Feedback">Feedback</option>
      <option value="Issue Report">Issue Report</option>      
      </select>
      <input name="email" id="email" type="text" placeholder="Email:" class="field_props" />      
      <select input name="source" id="source" type="text" value="" class="field_props" >
           <option value="">Known Taggerzz.com by </option>
      <option value="News papers ad">News papers ad</option>
      <option value="Friends">Friends</option>
      <option value="Website">Website</option>
      <option value="Network">Network</option>
      <option value="Internet & Email">Internet & Email</option>
      </select>

      <textarea name="requirement" id="requirement" cols="" rows="" class="field_props_desc" placeholder="Your Requirement"></textarea>
      <br />
	    <input type="text" id='captcha' class='field_props' placeholder="captcha" value="" /> 
		<input type="text" class="img_captcha" id="refreshCode" name="refreshCode" readonly="readonly" disabled="disabled"  value="">

	   <img class="img_refresh" src="imgs/refresh.png"  onclick="return refresh()" alt="refresh"/>
	   <br/>
	   	<button type="submit" id="submit" name="submit" >Send Message</button>

      <!-- <a href="#">
      <div class="submit_button_props" onclick="contact();" >Submit</div>
      </a> -->
</div>


</div>
</div>
</body>
</html>
<script>
$(document).ready(function() {
	refresh();
   });
function contact()
	{
	        //alert();
			var flag=true;
			if($('#cproject').val()==""){
				$('#cproject').focus();
				flag=false;
				alert('Please Select Your Request');return false;
			}
			if($('#email').val()==""){
				$('#email').focus();
				flag=false;
				alert('Please enter valid email');return false;
			}
			
			if($('#source').val()==""){
				$('#source').focus();
			flag=false;
			alert('Please Select Known Taggerzz.com by');return false;
			
			}
		
			if($('#requirement').val()==""){
				$('#requirement').focus();
			flag=false;
			alert('Please enter requirement');return false;
			
			}
			if($('#captcha').val()==""){
				flag=false;
				$('#captcha').focus();

			alert('Please enter captcha');return false;
			}else if($('#captcha').val()!=$("#refreshCode").val()){ 
			flag=false;
             alert("Please enter the correct code");return false;
	         
			}
			if(flag==false){
				return false;
            }else{
				return true;
				alert('sf');
				$( "#enquiryForm" ).submit();
						
			}
	}	
function refresh(){
	$.ajax({
		url: "captchacgetcode.php",
		type: "POST",				
		success: function(msg){ 
			$("#refreshCode").val(msg);
		},				
	});   
}
	$('#successMessage').delay(6000).fadeOut('slow');
	

</script>
