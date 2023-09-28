<?php
ob_start();

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$subject = trim($_POST['subject']);
$message = trim($_POST['message']);
$datetime = $_POST['datetime'];

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

if ($name && $email && $message) { // Check if name, email and message is empty
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) // Check if email format is valid
  {
    $isSuccess = false;
    $msg = 'Invalid email. Please check';
  } else {
    
    /* Email to recipient */
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = '';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = '';                     //SMTP username Your full Gmail address (e.g. you@gmail.com)
    $mail->Password   = '';                               //SMTP password The password that you use to log in to Gmail
    $mail->SMTPSecure = '';            //Enable implicit TLS encryption
    $mail->Port       = '';
    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS | 465 = PHPMailer::ENCRYPTION_SMTPS

    //Recipients
    $mail->setFrom($email, $name);
    $mail->addAddress('', '');               //Name is optional

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;

    include 'email.php';
    $emailContent = ob_get_clean(); // Email content will display here
    $mail->Body    = $emailContent;

    if (!$mail->send()) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mailToSelf->ErrorInfo;
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
      $isSuccess = true;
      $msg = 'Form submitted';
    }

  }
} else {
  $isSuccess = false;
  $msg = 'Please fill in all the fields.';
}
$data = array(
  'isSuccess' => $isSuccess,
  'msg' => $msg
);

echo json_encode($data);
