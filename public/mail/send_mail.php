<?php
	include_once('class.phpmailer.php');
	function sendMail($to,$subject,$message,$fromAddress='',$fromUserName='',$toName='',$bcc='',$upload_dir='', $filename='')
	{	
		
		$mail             	= new PHPMailer();
		$mail->IsSMTP();
		$mail->Host     	= "mail.taggerzz.com";
		$mail->Port  		= 25;
		$mail->IsSendmail();
		$mail->IsHTML(true);
		$mail->ClearAddresses();
		$find = strpos($to,',');		
		if($find)
		{
			$ids = explode(',',$to);
			for($i=0;$i<count($ids);$i++)
			{
				$mail->AddAddress($ids[$i]);
			}
		}
		else
		{
			$mail->AddAddress($to);
		}	
		
		if($fromAddress!=''){
			$mail->From     = $fromAddress;
		} else {
			$mail->From     = "no-reply@taggerzz.com";
		}
		if($fromUserName!=''){
			$mail->FromName = $fromUserName;
		} else {
			$mail->FromName = "TAGGERZZ";	
		}
		$mail->WordWrap = 50; 
		$mail->IsHTML(true);
		$mail->Subject 	= $subject;			
		$mail->Body 	= $message;
		if($upload_dir!='')
		{
			foreach($upload_dir as $uploaddirs)
			{
				$mail->AddAttachment($uploaddirs, $filename); 
			}
		}
		if($mail->Send())
		{
		
			return 1;	
		}
		else
		{
			return 0;	
		}
		
	}
	$deactiveMessage='
	<body style="margin: 0;padding: 0;">
       <div style="position: relative;margin-left: auto;margin-right: auto;width: 620px;height: 500px;overflow: hidden;z-index:0;border:1px solid #ddd; box-shadow:0 2px 2px rgba(0, 0, 0, 0.3);">
               <div style="border-bottom:1px solid #ddd;padding:10px 10px;background:#f7f7f7;">
                       <img style="width:100%;" src="https://taggerzz.com/public/img/imgLogo.png">
               </div>
               <div style="position:relative;padding:20px 30px;">
                       <div style="font-size:15px;">Dear   &nbsp;FULLNAME,</div>
                       <div style="width: 620px;height: 360px;display:table-cell;vertical-align:middle;text-align:center;">        
                               <p style="font-family: sinkin_sans500_medium, sans-serif;font-size:16px">MESSAGE</p>
							   <p style="font-family:sinkin_sans500_medium, sans-serif;font-weight:bold;">PASSWORDLINK</p>
                       </div> 
                       <div class=""><img style="width:100%;" src="https://taggerzz.com/public/img/imgLogo.png"/></div>
               </div>
       </div>
</body>

	<body style="margin: 0;padding: 0;">
       <div style="position: relative;margin-left: auto;margin-right: auto;width: 620px;height: 500px;overflow: hidden;z-index:0;border:1px solid #ddd; box-shadow:0 2px 2px rgba(0, 0, 0, 0.3);">
               <div style="border-bottom:1px solid #ddd;padding:10px 10px;background:#f7f7f7;">
                       <img style="width:100%;" src="https://taggerzz.com/public/img/imgLogo.png">
               </div>
               <div style="position:relative;padding:20px 30px;">
                       <div style="font-size:15px;">Dear   &nbsp;<FULLNAME>,</div>
                       <div style="width: 620px;height: 360px;display:table-cell;vertical-align:middle;text-align:center;">        
                               <p style="font-family: sinkin_sans500_medium, sans-serif;font-size:16px"><MESSAGE></p>
							   <p style="font-family:sinkin_sans500_medium, sans-serif;font-weight:bold;"><PASSWORDLINK></p>
                       </div> 
                       <div class=""><img style="width:100%;" src="https://taggerzz.com/public/img/imgLogo.png"/></div>
               </div>
       </div>
</body>

<br/><br/>
Regards,<br/>
Taggerzz.org Customer Service
</body>';
	// sendMail('bhargava.aapthi@gmail.com','subject','mesaage');
?>