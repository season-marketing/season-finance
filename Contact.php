<?php 
$to = "nathanmerry9713@gmail.com";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= "From: <{$_REQUEST['email']}>" . "\r\n";

$subject = "Season Finance Contact";

$message = "
    Name: {$_REQUEST['firstName']} {$_REQUEST['lastName']}\n\n
    Markets: {$_REQUEST['markets']}\n\n
    Phone: {$_REQUEST['phone']}\n\n
    Message: {$_REQUEST['message']}\n\n
";

// send email
$mail = mail($to,$subject,$message,$headers);

echo $mail;
