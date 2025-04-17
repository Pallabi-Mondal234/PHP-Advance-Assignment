<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Handles the email sending functionality.
 */
class Mailer {
  /**
   * PHPMailer instance used to configure and send emails.
   * 
   * @var PHPMailer
   */
  private $mail;

  /**
   * MailboxLayer API key used for email validation.
   * 
   * @var string 
   */
  private $apiKey = "9a9f6a177ecbd1a579eed0094486a81b";
  
  /**
  * Initializes the PHPMailer instance with SMTP configuration.
  */
  public function __construct() {
    // Initialize PHPMailer
    $this->mail = new PHPMailer(true);

    // SMTP Configuration
    $this->mail->isSMTP();
    $this->mail->Host       = 'smtp.gmail.com';
    $this->mail->SMTPAuth   = true;
    $this->mail->Username   = 'mondalpallabi388@gmail.com';
    $this->mail->Password   = 'zkhb ncvv mbdg nmpe';
    $this->mail->SMTPSecure = 'tls';
    $this->mail->Port       = 587;
  }

  /**
   * Check email is exists or not through api.
   * 
   * @var string $email 
   *   User's email address.
   * 
   * @return boolean 
   *   Return boolean value.
   */
  public function validate($email) {
    // Build the API URL
    $url = "https://apilayer.net/api/check?access_key=" . urlencode($this->apiKey) . "&email=" . urlencode($email);

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    // Execute the API request
    $response = curl_exec($ch);

    // Handle any cURL errors
    if ($response === FALSE) {
      error_log("cURL error: " . curl_error($ch));
      curl_close($ch);
      return FALSE;
    }

    curl_close($ch);

    // Decode the JSON response
    $data = json_decode($response, TRUE);
    
    //Return true if format_valid and smtp both are valid.
    if ($data['format_valid'] === TRUE && $data['smtp_check'] === TRUE){
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Send a welcome email
   *
   * @param string $email 
   *   The email address of the recipient
   * 
   * @return string 
   *   Success or error message
   */
  public function sendWelcomeEmail($email) {
    try {
      // Sender and recipient
      $this->mail->setFrom('mondalpallabi388@gmail.com', 'Pallabi Mondal');
      $this->mail->addAddress($email);

      // Email content
      $this->mail->isHTML(TRUE);
      $this->mail->Subject = 'Welcome!';
      $this->mail->Body    = "<p>Thank you for your submission, $email!</p>";

      // Attempt to send the email
      if (!$this->mail->send()) {
        return "Mailer Error: {$this->mail->ErrorInfo}";
      }

      return $email;
    } 
    catch (Exception $e) {
      // Catch and return any exceptions from PHPMailer
      return "Mailer Error: {$this->mail->ErrorInfo}<br>Exception Message: {$e->getMessage()}";
    }
  }
}


