<?php
namespace App\Services;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailService extends AbstractService
{
	public function mailer() : PHPMailer
	{
		$mail = new PHPMailer(true);
		//Server settings
		$mail->SMTPDebug	= SMTP::DEBUG_OFF;					//Enable verbose debug output
		$mail->isSMTP();										//Send using SMTP
		$mail->Host 		= env('MAILER_HOST', '127.0.0.1');	//Set the SMTP server to send through
		//$mail->SMTPAuth   = true;								//Enable SMTP authentication
		//$mail->Username   = 'user@example.com';				//SMTP username
		//$mail->Password   = 'secret';							//SMTP password
		//$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;		//Enable implicit TLS encryption
		$mail->Port 		= env('MAILER_PORT', 25);
		return $mail;
	}

	/**
	 * Create a message and send it.
	 * Uses the sending method specified by $Mailer.
	 * 
	 * @param  string       $subject
	 * @param  string       $body
	 * @param  mixed        $from
	 * @param  mixed        $to
	 * @param  mixed|null   $cc
	 * @param  mixed|null   $bcc
	 * @param  bool|boolean $is_email_html
	 *
	 * @throws Exception 
	 * @return bool false on error - See the ErrorInfo property for details of the error
	 */
	public function sendEmail(string $subject, string $body, mixed $from, mixed $to, mixed $cc = null, mixed $bcc = null, bool $is_email_html = true): bool
	{
		$mail = $this->mailer();
		if (is_string($from)) {
			$mail->setFrom($from);
			$mail->addReplyTo($from);
		} else if(is_array($from)){
			foreach ($from as $email => $name) {
				$mail->setFrom($email, $name);
				$mail->addReplyTo($email, $name);
			}
		}

		if (is_string($to)) {
			$mail->addAddress($to);
		} else if(is_array($to)){
			foreach ($to as $email => $name) {
				$mail->addAddress($email, $name);
			}
		}

		if (is_string($cc)) {
			$mail->addCC($cc);
		} else if(is_array($cc)){
			foreach ($cc as $email => $name) {
				$mail->addCC($email, $name);
			}
		}

		if (is_string($bcc)) {
			$mail->addBCC($bcc);
		} else if(is_array($bcc)){
			foreach ($bcc as $email => $name) {
				$mail->addBCC($email, $name);
			}
		}
		//Content
		$mail->isHTML($is_email_html);
		$mail->Subject = $subject;
		$mail->Body    = $body;
		return $mail->send();
	}
}
?>