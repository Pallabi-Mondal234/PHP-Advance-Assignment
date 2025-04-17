<?php
require_once 'Mailer.php';

//Check if the request method is POST and email is set or not.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
  //Trim the email input.
  $email = trim($_POST['email']);

  //Create object of Mailer class.
  $mailer = new Mailer();

  //Check format validity of email.
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit;
  }

  //Validate the email through Mailer class validate function.
  if (!$mailer->validate($email)) {
    echo "Invalid or undeliverable email address.";
    exit;
  }

  //Send the email using Mailer class function sendWelcomeEmail.
  $result = $mailer->sendWelcomeEmail($email);

  //Check if the sending email was successfull or not.
  if ($result === $email) {
    echo "success";
  }
  else {
    echo $result;
  }
}
else {
  //handle invalid request.
  echo "Invalid request.";
}
?>

