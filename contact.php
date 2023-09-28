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
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'dominicleewenghin@gmail.com';                     //SMTP username Your full Gmail address (e.g. you@gmail.com)
    $mail->Password   = 'tljl pmqg ilrm sofx';                               //SMTP password The password that you use to log in to Gmail
    $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
    $mail->Port       = 465;
    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS | 465 = PHPMailer::ENCRYPTION_SMTPS

    //Recipients
    $mail->setFrom($email, $name);
    $mail->addAddress('dominicleewenghin@gmail.com', 'Dominic');               //Name is optional

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

      // // SMTP configuration (common for both recipients)
      // $mail->isSMTP();
      // $mail->Host = 'smtp.gmail.com';
      // $mail->SMTPAuth = true;
      // $mail->Username = 'dominicleewenghin@gmail.com'; // Your Gmail email address
      // $mail->Password = 'tljl pmqg ilrm sofx'; // Your Gmail password
      // $mail->SMTPSecure = 'ssl';
      // $mail->Port = 465;

      // // Set the sender's information (you can customize this as needed)
      // $mail->setFrom('dominicleewenghin@gmail.com', 'Dominic');

      // // Define the recipients and their respective email content
      // $recipients = [
      //     ['email' => $email, 'name' => $name, 'content_file' => 'Testing.php'],
      //     ['email' => 'dominicleewenghin@gmail.com', 'name' => 'Dominic', 'content_file' => 'email.php'],
      // ];

      // foreach ($recipients as $recipientInfo) {
      //     $email = $recipientInfo['email'];
      //     $name = $recipientInfo['name'];
      //     $contentFile = $recipientInfo['content_file'];

      //     // Add the recipient
      //     $mail->addAddress($email, $name);

      //     // Include the content from the PHP file
      //     include $contentFile;
      //     $content = ob_get_clean();


      //     // Set the email content
      //     $mail->isHTML(true);
      //     $mail->Subject = 'Your Subject';
      //     $mail->Body = $content;

      //     // Send the email
      //     if ($mail->send()) {
      //         echo "Email sent to $email<br>";
      //     } else {
      //         echo "Email could not be sent to $email. Error: " . $mail->ErrorInfo . "<br>";
      //     }

      //     // Clear recipients for the next iteration
      //     $mail->clearAddresses();

      //     $mail->Body = '';
      // }
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
