<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

class Mailer
{
    private $smtp_host = '';
    private $smtp_user = '';
    private $smtp_pass = '';
    private $smtp_port = '';
    private $name = '';
    private $from = '';
    private $to = '';
    private $replyto = '';
    private $subject = '';
    private $msg = '';

    public function __construct($config = false)
    {
        if (isset($config['smtp_host'])) $this->smtp_host = $config['smtp_host'];
        if (isset($config['smtp_user'])) $this->smtp_user = $config['smtp_user'];
        if (isset($config['smtp_pass'])) $this->smtp_pass = $config['smtp_pass'];
        if (isset($config['smtp_port'])) $this->smtp_port = $config['smtp_port'];
        if (isset($config['name'])) $this->name = $config['name'];
        if (isset($config['from'])) $this->from = $config['from'];
        if (isset($config['to'])) $this->to = $config['to'];
        if (isset($config['replyto'])) $this->replyto = $config['replyto'];
        if (isset($config['subject'])) $this->subject = $config['subject'];
        if (isset($config['msg'])) $this->msg = $config['msg'];
    }

    public function send(){
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = $this->smtp_host;                            // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = $this->smtp_user;                        // SMTP username
            $mail->Password   = $this->smtp_pass;                        // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = $this->smtp_port;                            // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom($this->from, $this->name);
            //$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress($this->to);               // Name is optional
            $mail->addReplyTo($this->replyto, 'Support');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $this->subject;
            $mail->Body    = $this->msg;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if ($mail->send()) {
                return true;
            } else {
                return false;
            }
            //echo 'Message has been sent';
            return TRUE;
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return FALSE;
        }
    }
}