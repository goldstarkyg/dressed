<?php
/**
 * Simple example script using PHPMailer with exceptions enabled
 * @package phpmailer
 * @version $Id$
 */
header('Content-Type: text/html; charset=utf-8');
require '../class.phpmailer.php';
if(isset($_REQUEST['data'])){
	$data = json_decode($_REQUEST['data'], JSON_UNESCAPED_UNICODE);
	for($i = 0; $i < count($data); $i++) {
		$email = $data[$i]['email'];
		//$fmail = explode('||', $email);
		//if(count($fmail) > 1)
		//$email = $fmail;
		$content = $data[$i]['content'];
		$subject = $data[$i]['subject'];
		$fromname= $data[$i]['fromname'];
		try {
			$mail = new PHPMailer(true); //New instance, with exceptions enabled

			$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html>  <head>
			<meta http-equiv="Content-Type" content="text/html; charset=JIS">  </head>  <body><p>' . $content . '</p>
				</body>
				</html>';
			$body = preg_replace('/\\\\/', '', $body); //Strip backslashes

			$mail->IsSMTP();                           // tell the class to use SMTP
			$mail->SMTPAuth = true;                  // enable SMTP authentication
			$mail->Port = 587;//25;                    // set the SMTP server port
			$mail->Host = "mail.dressd.us";//"mail.safety-motocle.com"; // SMTP server
			$mail->Username = "contact@dressd.us";     // SMTP server username
			$mail->Password = "Contact123$";            // SMTP server password

			$mail->IsSendmail();  // tell the class to use Sendmail
			$mail->From = "contact@dressd.us";
			$mail->FromName = $fromname;//mb_convert_encoding('世界WiFi 事務局', "JIS", "UTF-8");//"Sekai WiFi";//

			//$to = $email;
			$to = $email;
			//$mail -> charSet = "UTF-8";
			$mail->CharSet = "UTF-8";
			$mail->AddAddress($to);

			$mail->Subject = $subject;
			///$mail->setLanguage("jp");
			//$mail->WordWrap   = 1000; // set word wrap
			$mail->MsgHTML($body);
			//$mail->Body  = $body;

			$mail->IsHTML(true); // send as HTML

			$mail->Send();
			//echo 'Message has been sent.';
		} catch (phpmailerException $e) {
			//echo $e->errorMessage();
			return 0;
		}
	}
	return 1;
}
?>



