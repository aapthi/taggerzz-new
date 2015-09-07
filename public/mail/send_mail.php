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
	// sendMail('bhargava.aapthi@gmail.com','subject','mesaage');
?>