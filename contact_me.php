<?php

//error_reporting(E_ALL);
//include_once('PHPMailer-master/class.phpmailer.php');

//if(isset($_REQUEST['userMessage']))
//{
//    $to_Email       = "test@oceanbrains.com"; //Replace with recipient email address
//    $subject        = 'Mind Grids'; //Subject line for emails
if($_POST)
{
    $to_Email       = "test@oceanbrains.com"; //Replace with recipient email address
    $subject        = 'Mind Grids'; //Subject line for emails   
   
   
  	//$mail->SetFrom( "test@oceanbrains.com", "test@oceanbrains.com");
//	$mail->AddAddress("test@oceanbrains.com", "test@oceanbrains.com");//
//	$mail->Subject  		= "Mind Grids";
//	
//
//	$body	 =	"Hi, <br/>
//	The required is given below<br/><br/>
//	<table>";
//	$body	.=	"<tr><td style='width:400px; font-weight:bold;'>application date:</td><td style='width:500px;'>".."</td></tr>";
//	
//	$body	.=	"</table><br/>regards,<br/>xyz	";
//	$mail->MsgHTML($body);
//	echo $mail->Send();
   
   
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
   
        //exit script outputting json data
        $output = json_encode(
        array(
            'type'=>'error',
            'text' => 'Request must come from Ajax'
        ));
       
        die($output);
    }
   
    //check $_REQUEST vars are set, exit if any missing
    if(!isset($_REQUEST["userName"]) || !isset($_REQUEST["userEmail"]) || !isset($_REQUEST["userPhone"]) || !isset($_REQUEST["userMessage"]))
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
        die($output);
    }

    //Sanitize input data using PHP filter_var().
    $user_Name        = filter_var($_REQUEST["userName"], FILTER_SANITIZE_STRING);
    $user_Email       = filter_var($_REQUEST["userEmail"], FILTER_SANITIZE_EMAIL);
    $user_Phone       = filter_var($_REQUEST["userPhone"], FILTER_SANITIZE_STRING);
    $user_Message     = filter_var($_REQUEST["userMessage"], FILTER_SANITIZE_STRING);
   
    //additional php validation
    if(strlen($user_Name)<4) // If length is less than 4 it will throw an HTTP error.
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
        die($output);
    }
    if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //email validation
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
        die($output);
    }
    if(!is_numeric($user_Phone)) //check entered data is numbers
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Only numbers allowed in phone field'));
        die($output);
    }
    if(strlen($user_Message)<5) //check emtpy message
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something.'));
        die($output);
    }
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	$email_message = "Form details below.\n\n<br><br>";
 
     
 
    function clean_string($string) {
 
      $bad = array("content-type","bcc:","to:","cc:","href");
 
      return str_replace($bad,"",$string);
 
    }
 
$email_message .= '<table width="800" border="0">
  <tr>
    <td style="background:#ccc; color:#000; padding:5px; width:120; font-weight:bolder;">User Name:</td>
    <td>'.clean_string($user_Name).'</td>
  </tr>
  <tr>
    <td style="background:#ccc; color:#000; padding:5px; width:120; font-weight:bolder;">Job Title:</td>
    <td>'.clean_string($user_Email).'</td>
  </tr>
  <tr>
    <td style="background:#ccc; color:#000; padding:5px; width:120; font-weight:bolder;">Company Name:</td>
    <td>'.clean_string($user_Phone).'</td>
  </tr>
  <tr>
    <td style="background:#ccc; color:#000; padding:5px; width:120; font-weight:bolder;">Email:</td>
    <td>'.clean_string($user_Message).'</td>
  </tr>
</table>';
	   
    //proceed with PHP email.
    $headers = 'From: '.$user_Email.'' . "\r\n" .
    'Reply-To: '.$user_Email.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
   
        // send mail
    $sentMail = @mail($to_Email, $subject, $email_message, $headers);
   
    if(!$sentMail)
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_Name .' Thank you for your email'));
        die($output);
    }
}
?>
