<?php
include('public/mail/send_mail.php');
/*Forget Password*/
global $layoutTitle;
global $layoutImage;
global $layoutDescription;
global $layoutUrl;
global $layoutKeywords;
global $loginType;
global $step;
global $isPrivateBox;
global $dashBoardSubject;
global $dashBoardMessage;
global $pwdChangedSubject;
global $pwdChangedMessage;
global $forgotPwdPage;
global $browserDispPage;
global $errorDispPage;
global $fpPwdSubject;
global $fpPwdMessage;
global $iniviteFriendSubject;
global $iniviteFriendMessage;

global $pageLocation;
if($_SERVER['HTTP_HOST']=='localhost'){
	$pageLocation='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}else if($_SERVER['HTTP_HOST']=='staging.taggerzz.com'){
	$pageLocation='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
global $homePageYes;
$homePageYes="";

global $facebook_login;
$facebook_login='/user/login/facebook';

global $google_login;
$google_login='/user/login/google';

global $facebookAppId;
$facebookAppId='808558082540988';
/*
$dashBoardSubject = "TAGGERZZ DASHBOARD";
$dashBoardMessage = '<body>
		<table width="600" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><table width="600" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #D63C2B">
			<tr><td bgcolor="#D63C2B">
				<a href="javascript:void(0);" target="_blank" style="text-decoration: none;">
				<span style="color:#fff; font:normal 30px arial">Taggerzz.Org</span></a></td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="10" align="left">
						<tr><td><a href="javascript:void(0);" style="color:#4ca4b6; font:bold 12px arial; text-decoration:none;">Hello&nbsp;&nbsp;&nbsp;<FULLNAME></a></td></tr>
						<tr><td><MESSAGE></td></tr>
						<tr><td>Security Code&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<SECURITYCODE></td></tr>
						<tr><td>Hash Name&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<HASHNAME></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Sincerely,</td></tr>
						<tr><td>Taggerzz.Org Team</td></tr>
					</table>
				</td>
			</tr>  
			</table></td>
		</tr> 
	</table>
<br/><br/>
Regards,<br/>
Taggerzz.Org Customer Service
</body>';
*/
/*End Forget Password*/

/* register subject 
global $regSubject;
global $regMessage;
$regSubject= "Registration confirmation";
$regMessage='<body>
		<table width="600" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><table width="600" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #D63C2B ">
			<tr><td bgcolor="#D63C2B ">
				<a href="Javascript:void(0);" target="_blank" style="text-decoration: none;">
				<span style="color:#fff; font:normal 30px arial">Taggerzz.Org</span></a></td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="10" align="left">
						<tr><td><a href="javascript:void(0);" style="color:#D63C2B ; font:bold 12px arial; text-decoration:none;">Hello&nbsp;&nbsp;&nbsp;<FULLNAME></a></td></tr>
						<tr><td>Please click below link to email conformation</td></tr>
						<tr><td><a href="<REGLINK>"><REGLINK></a></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Sincerely,</td></tr>
						<tr><td>Taggerzz.Org Team</td></tr>
					</table>
				</td>
			</tr>  
			</table></td>
		</tr> 
	</table>
<br/><br/>
Regards,<br/>
Taggerzz.org Customer Service
</body>';
*/
//Deactivate Account

/*
global $deactiveSubject;
global $deactiveMessage;
$deactiveSubject= "TAGGERZZ";
$deactiveMessage='<body>
		<table width="600" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><table width="600" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #D63C2B ">
			<tr><td bgcolor="#D63C2B ">
				<a href="Javascript:void(0);" target="_blank" style="text-decoration: none;">
				<span style="color:#fff; font:normal 30px arial">Taggerzz.Org</span></a></td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="10" align="left">
						<tr><td><a href="javascript:void(0);" style="color:#D63C2B ; font:bold 12px arial; text-decoration:none;">Hello&nbsp;&nbsp;&nbsp;<FULLNAME></a></td></tr>
						<tr><td>Please click below link to relogin . Then your account is Activated</td></tr>
						<tr><td><a href="<REGLINK>"><REGLINK></a></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Sincerely,</td></tr>
						<tr><td>Taggerzz.Org Team</td></tr>
					</table>
				</td>
			</tr>  
			</table></td>
		</tr> 
	</table>
<br/><br/>
Regards,<br/>
Taggerzz.org Customer Service
</body>';

global $accessDetailsSubject;
global $accessDetailsMessage;
$accessDetailsSubject = "Taggerzz Accessing Details.";
$accessDetailsMessage = '<body>
		<table width="600" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><table width="600" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #D63C2B">
			<tr><td bgcolor="#D63C2B">
				<a href="javascript:void(0);" target="_blank" style="text-decoration: none;">
				<span style="color:#fff; font:normal 30px arial">Taggerzz.Org</span></a></td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="10" align="left">
						<tr><td><a href="javascript:void(0);" style="color:#4ca4b6; font:bold 12px arial; text-decoration:none;">Hello&nbsp;&nbsp;&nbsp;<FULLNAME></a></td></tr>
						<tr><td><MESSAGE></td></tr>
						<tr><td>Category Name&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<CATEGORYNAME></td></tr>
						<tr><td>Security Code&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<SECURITYCODE></td></tr>
						<tr><td>Hash Name&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<HASHNAME></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Sincerely,</td></tr>
						<tr><td>Taggerzz.Org Team</td></tr>
					</table>
				</td>
			</tr>  
			</table></td>
		</tr> 
	</table>
<br/><br/>
Regards,<br/>
Taggerzz.Org Customer Service
</body>';

global $databoxCreatedSubject;
global $databoxCreatedMessage;
$databoxCreatedSubject = "Taggerzz Category Created.";
$databoxCreatedMessage = '<body>
		<table width="600" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><table width="600" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #D63C2B">
			<tr><td bgcolor="#D63C2B">
				<a href="javascript:void(0);" target="_blank" style="text-decoration: none;">
				<span style="color:#fff; font:normal 30px arial">Taggerzz.Org</span></a></td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="10" align="left">
						<tr><td><a href="javascript:void(0);" style="color:#4ca4b6; font:bold 12px arial; text-decoration:none;">Hello&nbsp;&nbsp;&nbsp;<FULLNAME></a></td></tr>
						<tr><td><MESSAGE></td></tr>
						<tr><td>Category Name&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<CATEGORYNAME></td></tr>
						<tr><td>Hash Name&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<HASHNAME></td></tr>
						<tr><td>Security Code&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<SECURITYCODE></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td>Sincerely,</td></tr>
						<tr><td>Taggerzz.Org Team</td></tr>
					</table>
				</td>
			</tr>  
			</table></td>
		</tr> 
	</table>
<br/><br/>
Regards,<br/>
Taggerzz.Org Customer Service
</body>';
*/

// comment on 6/29/2016

/* global $databoxCreatedSubject;
global $databoxCreatedMessage;
$databoxCreatedSubject = "Taggerzz Category Created.";
$databoxCreatedMessage = '<body style="margin: 0;padding: 0;">
       <div style="position: relative;margin-left: auto;margin-right: auto;width: 620px;height: 500px;overflow: hidden;z-index:0;border:1px solid #ddd; box-shadow:0 2px 2px rgba(0, 0, 0, 0.3);">
               <div style="border-bottom:1px solid #ddd;padding:10px 10px;background:#f7f7f7;">
                       <img style="width:100%;" src="https://taggerzz.com/public/img/imgLogo.png">
               </div>
               <div style="position:relative;padding:20px 30px;">
                       <div style="font-size:15px;">Dear   &nbsp;<FULLNAME>,</div>
                       <div style="width: 620px;height: 360px;display:table-cell;vertical-align:middle;text-align:center;">        
                               <p style="font-family: sinkin_sans500_medium, sans-serif;font-size:16px">Your Databox has been created ! <b>( <MESSAGE> / 10 Available)</b></p>
                               <p style="font-family: sinkin_sans500_medium, sans-serif;">Category:   &nbsp;<CATEGORYNAME> &raquo; Category &raquo;   &nbsp;<CATEGORYNAME></p>
                               <p style="font-family:sinkin_sans500_medium, sans-serif;font-weight:bold;">Your accesible Hashtag :   &nbsp;<HASHNAME></p>
                               <p style="font-family:sinkin_sans500_medium, sans-serif;font-weight:bold;"><SECURITYCODE></p>                
                       </div> 
                       <div class=""><img style="width:100%;" src="https://taggerzz.com/public/img/imgLogo.png"/></div>
               </div>
       </div>
</body>'; */
global $databoxCreatedSubject;
global $databoxCreatedMessage;
$databoxCreatedSubject = "Taggerzz Category Created.";
$databoxCreatedMessage = '<body style="margin: 0;padding: 0;">
<div style="position: relative; margin-left: auto; margin-right: auto; width: 620px; height: auto; overflow: hidden; z-index:0; border:1px solid #ddd; box-shadow:0 0px 0px rgba(0, 0, 0, 0.3);">
  <div style="border-bottom:1px solid #ddd;padding:10px 10px;background:#f7f7f7;"> <img style="width:30%;" src="https://taggerzz.com/public/img/imgLogo.png"> </div>
  <div style="position:relative;padding:20px 30px;">
    <div style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;">Dear <FULLNAME>,</div>
    <div style="width: 620px;height: 360px;display:table-cell;vertical-align:middle;text-align:center;">
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px">Your <CATEGORYNAME> has been created and accessible on taggerzz.com with </p>
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.50em;"> <HASHNAME></p>
	  <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;"> <SECURITYCODE></p>      

      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;">Your <CATEGORYNAME> Contains <LINKS> Web/Hot Links and <KEYWORDS> keywords</p>
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;">You have earned <POINTS> Points for creating this <CATEGORYNAME>. </p>
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;">Your Total rewarded Points:<b style="font-size:1.50em;"> <POINTS> worth of <AMOUNT> Inr </b></p>
      <div>
        <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;"><a href="https://taggerzz.com/forgot-password" target="_blank"><b>I did not Created this</b></a></p>
      </div>
    </div>
    <div><p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;"><a href="https://taggerzz.com" target="_blank"><b>Visit Taggerzz.com</b></a></p></div>
  </div>
</div>
</body>';
global $accessDetailsSubject;
global $accessDetailsMessage;
$accessDetailsSubject = "Taggerzz Accessing Details.";
$accessDetailsMessage = '<body style="margin: 0;padding: 0;">
<div style="position: relative; margin-left: auto; margin-right: auto; width: 620px; height: auto; overflow: hidden; z-index:0; border:1px solid #ddd; box-shadow:0 0px 0px rgba(0, 0, 0, 0.3);">
  <div style="border-bottom:1px solid #ddd;padding:10px 10px;background:#f7f7f7;"> <img style="width:30%;" src="https://taggerzz.com/public/img/imgLogo.png"> </div>
  <div style="position:relative;padding:20px 30px;">
    <div style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;">Dear <FULLNAME>,</div>
    <div style="width: 620px;height: 360px;display:table-cell;vertical-align:middle;text-align:center;">
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px"><MESSAGE></p>
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px">Category:   &nbsp; <CATEGORYNAME></p>
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px"><SECURITYCODE></p>
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px">Hashtag Name: &nbsp;<HASHNAME></p>
    </div>
    <div><p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;"><a href="https://taggerzz.com" target="_blank"><b>Visit Taggerzz.com</b></a></p></div>
  </div>
</div>
</body>';
$dashBoardSubject = "TAGGERZZ DASHBOARD";
$dashBoardMessage = '<body style="margin: 0;padding: 0;">
<div style="position: relative; margin-left: auto; margin-right: auto; width: 620px; height: auto; overflow: hidden; z-index:0; border:1px solid #ddd; box-shadow:0 0px 0px rgba(0, 0, 0, 0.3);">
  <div style="border-bottom:1px solid #ddd;padding:10px 10px;background:#f7f7f7;"> <img style="width:30%;" src="https://taggerzz.com/public/img/imgLogo.png"> </div>
  <div style="position:relative;padding:20px 30px;">
    <div style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;">Dear <FULLNAME>,</div>
    <div style="width: 620px;height: 360px;display:table-cell;vertical-align:middle;text-align:center;">
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px"><MESSAGE></p>
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px">Security Code&nbsp;&nbsp;<SECURITYCODE></p>
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px">Hashtag Name:   &nbsp;<HASHNAME></p>
    </div>
    <div><p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;"><a href="https://taggerzz.com" target="_blank"><b>Visit Taggerzz.com</b></a></p></div>
  </div>
</div>
</body>';
global $regSubject;
global $regMessage;
$regSubject= "Registration confirmation";
$regMessage='<body style="margin: 0;padding: 0;">
<div style="position: relative; margin-left: auto; margin-right: auto; width: 620px; height: auto; overflow: hidden; z-index:0; border:1px solid #ddd; box-shadow:0 0px 0px rgba(0, 0, 0, 0.3);">
  <div style="border-bottom:1px solid #ddd;padding:10px 10px;background:#f7f7f7;"> <img style="width:30%;" src="https://taggerzz.com/public/img/imgLogo.png"> </div>
  <div style="position:relative;padding:20px 30px;">
    <div style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;">Dear <FULLNAME>,</div>
    <div style="width: 620px;height: 360px;display:table-cell;vertical-align:middle;text-align:center;">
        <p style="font-family: sinkin_sans500_medium, sans-serif;font-size:16px">Please click below link to email conformation</p>
        <p style="font-family:sinkin_sans500_medium, sans-serif;font-weight:bold;"><a href="<REGLINK>"><REGLINK></a></p> 
    </div>
    <div><p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;"><a href="https://taggerzz.com" target="_blank"><b>Visit Taggerzz.com</b></a></p></div>
  </div>
</div>
</body>';
global $deactiveSubject;
global $deactiveMessage;
$deactiveSubject= "TAGGERZZ";
$deactiveMessage='<body style="margin: 0;padding: 0;">
<div style="position: relative; margin-left: auto; margin-right: auto; width: 620px; height: auto; overflow: hidden; z-index:0; border:1px solid #ddd; box-shadow:0 0px 0px rgba(0, 0, 0, 0.3);">
  <div style="border-bottom:1px solid #ddd;padding:10px 10px;background:#f7f7f7;"> <img style="width:30%;" src="https://taggerzz.com/public/img/imgLogo.png"> </div>
  <div style="position:relative;padding:20px 30px;">
    <div style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;">Dear <FULLNAME>,</div>
    <div style="width: 620px;height: 360px;display:table-cell;vertical-align:middle;text-align:center;">
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px">Please click below link to relogin . Then your account is Activated</p>
        <p style="font-family:sinkin_sans500_medium, sans-serif;font-weight:bold;"><a href="<REGLINK>"><REGLINK></a></p> 
    </div>
    <div><p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;"><a href="https://taggerzz.com" target="_blank"><b>Visit Taggerzz.com</b></a></p></div>
  </div>
</div>
</body>';
$pwdChangedSubject = "Your Taggerzz Password Changed.";
$pwdChangedMessage = '<body style="margin: 0;padding: 0;">
<div style="position: relative; margin-left: auto; margin-right: auto; width: 620px; height: auto; overflow: hidden; z-index:0; border:1px solid #ddd; box-shadow:0 0px 0px rgba(0, 0, 0, 0.3);">
  <div style="border-bottom:1px solid #ddd;padding:10px 10px;background:#f7f7f7;"> <img style="width:30%;" src="https://taggerzz.com/public/img/imgLogo.png"> </div>
  <div style="position:relative;padding:20px 30px;">
    <div style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;">Dear <FULLNAME>,</div>
    <div style="width: 620px;height: 360px;display:table-cell;vertical-align:middle;text-align:center;">
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px"><MESSAGE></p>
    </div>
    <div><p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;"><a href="https://taggerzz.com" target="_blank"><b>Visit Taggerzz.com</b></a></p></div>
  </div>
</div>
</body>';
$fpPwdSubject = "Taggerzz Password Reset Link.";
$fpPwdMessage = '<body style="margin: 0;padding: 0;">
<div style="position: relative; margin-left: auto; margin-right: auto; width: 620px; height: auto; overflow: hidden; z-index:0; border:1px solid #ddd; box-shadow:0 0px 0px rgba(0, 0, 0, 0.3);">
  <div style="border-bottom:1px solid #ddd;padding:10px 10px;background:#f7f7f7;"> <img style="width:30%;" src="https://taggerzz.com/public/img/imgLogo.png"> </div>
  <div style="position:relative;padding:20px 30px;">
    <div style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;">Dear <FULLNAME>,</div>
    <div style="width: 620px;height: 360px;display:table-cell;vertical-align:middle;text-align:center;">
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px"><MESSAGE></p>
	  <p style="font-family:sinkin_sans500_medium, sans-serif;font-weight:bold;"><PASSWORDLINK></p>
    </div>
    <div><p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;"><a href="https://taggerzz.com" target="_blank"><b>Visit Taggerzz.com</b></a></p></div>
  </div>
</div>
</body>';
$iniviteFriendSubject = "Inivite to Taggerzz.";
$iniviteFriendMessage = '<body style="margin: 0;padding: 0;">
<div style="position: relative; margin-left: auto; margin-right: auto; width: 620px; height: auto; overflow: hidden; z-index:0; border:1px solid #ddd; box-shadow:0 0px 0px rgba(0, 0, 0, 0.3);">
  <div style="border-bottom:1px solid #ddd;padding:10px 10px;background:#f7f7f7;"> <img style="width:30%;" src="https://taggerzz.com/public/img/imgLogo.png"> </div>
  <div style="position:relative;padding:20px 30px;">
    <div style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;">Dear &nbsp;Friend,</div>
    <div style="width: 620px;height: 360px;display:table-cell;vertical-align:middle;text-align:center;">
      <p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px"><MESSAGE></p>
        <p style="font-family:sinkin_sans500_medium, sans-serif;font-weight:bold;"><SITELINK></p> 
    </div>
    <div><p style="font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:1.00em;"><a href="https://taggerzz.com" target="_blank"><b>Visit Taggerzz.com</b></a></p></div>
  </div>
</div>
</body>';
