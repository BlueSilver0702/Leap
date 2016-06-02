<?php
use Parse\ParseClient;
use Parse\ParseUser;
use Parse\ParseObject;
use Parse\ParseSessionStorage;
//use Aloha\Twilio;
/*
Invitation API 
- Parameters 
@calltoaction - text or email
@leapcode - 4 or 5 digit code string
@username - full name string
@groupname - group name string
@description Clients will call this API To add someone into the group, the API will send an invitation URL or instructions depending on what is being inputted, email or password. It will email them a link to Anyleap.com and then give them the Leap Code. The text will send them the app link and then send them the leap code to the group. 
*/
$calltoaction = $data['calltoaction'];
$leapcode     = $data['leapcode'];
$username     = $data['username'];
$groupname    = $data['groupname'];
$arr;
/*
Invitation Block
- Without Message
*/

if ($calltoaction != NULL || $leapcode != NULL || $username != NULL || $groupname != NULL) {
    
    if (strlen($leapcode) == 4 || strlen($leapcode) == 5) {
        
        if (filter_var($data['calltoaction'], FILTER_VALIDATE_EMAIL)) {
            /*
            Invitation through email message
            @Amazon SES request
            # == email address
            */
            
            
            require_once('class.phpmailer.php');
            
            //SMTP Settings
            $mail = new PHPMailer();
            $mail->IsHTML(true);
//$mail->CharSet = "text/html; charset=UTF-8;";
$mail->IsSMTP();
            //$mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "tls";
            $mail->Host       = "email-smtp.us-west-2.amazonaws.com";
            $mail->Username   = "AKIAJTCPKDMKZ224TVVA";
            $mail->Password   = "AqNretycHrL0kYX6dSpbHc+q5WN3A+6NdE5xqys1eCUz";
            //
            
            $mail->SetFrom('team@anyleap.com', 'Leap'); //from (verified email address)
            $mail->Subject = $groupname."'s invite into group"; //subject
            $body = "";
            //message
            if ($username !== "false") {
            $body .= $username.", you have been invited you to join ".$groupname."'s group experience on Leap<br />";
        } else { 
            $body .= "You have been invited to join".$groupname." on Leap<br />";
        }
        $body .= "Download the Leap app or go to <a href='http://anyleap.com'>anyleap.com</a> and enter the leap code to join! <br /><br />Leap code: <strong>".$leapcode."</strong><br /><br />";
        $body .= "<a href='http://anyleap.com'> <img src='https://play.google.com/intl/en_us/badges/images/generic/en-play-badge.png' height='50px' /></a>&nbsp;";
        $body .= "<a href='http://anyleap.com'> <img src='https://upload.wikimedia.org/wikipedia/commons/5/5d/Available_on_the_App_Store_(black).png' height='50px' /></a>";


            //$body = eregi_replace("[]", '', $body);
            //$body = preg_match('/[]]/i', '', $body);
            $mail->MsgHTML($body);
            //
            
            //recipient
            $mail->AddAddress($calltoaction);
            
            //Success
            if ($mail->Send()) {
                 $arr = array(
                'message' => 'Successfully sent invitation via email'
                );
                 echo json_encode($arr);
                die;
            }
            
            //Error
            if (!$mail->Send()) {
               // echo "Mailer Error: " . $mail->ErrorInfo;
                $arr = array(
                'message' => 'Mail error: '.$mail->ErrorInfo
            );
            }
           
            
            
            
            
            
        } else {
            /*
            Invitation through text message 
            @Twilio CURL request
            # != email address
            */
            
            
            $calltoaction = preg_replace('/\D/', '', $calltoaction);
            $calltoaction = "+1" . $calltoaction;
            
            if ($username !== "false") {
                $body = $username . ", you have been invited you to join " . $groupname . "'s group experience. Get the Leap app (iOS/Android) (or anyleap.com) \n\nLeap code: " . $leapcode;
            } else {
                $body = "You have been invited into " . $groupname . "'s group experience. Get the Leap app (iOS/Android) (or anyleap.com) \n\nLeap code: " . $leapcode;
            }
            
            $data = send_sms($calltoaction, $body);
            
            /*
            Change this depending on the body of the message
            */
            if (strpos($data, 'anyleap.com') !== false) {
                $arr = array(
                    'message' => 'Successfully sent invitation via text'
                );
                
            } else {
                $arr = array(
                    'message' => 'Failed to send invitation via text'
                );
            }
            
        }
        
    } else {
        $arr = array(
            'message' => 'Error processing request: Leap code'
        );
    }
    
} else {
    $arr = array(
        'message' => 'Error processing request: Invalid parameters'
    );
}




/*
Show response in JSON format 
*/
if (!empty($arr)) {
    echo json_encode($arr);
} else {
    $arr = array(
        'message' => 'Something went wrong'
    );
}




/*
Functions 
@required for SMS and SES  emai and text message sending
*/
function send_sms($to, $body)
{
    $from  = "+17609069086";
    $id    = "ACaf9a8fffd12d0d88315c73ab2a30e9ce";
    $token = "54913b4abcb39165350fefac0cb30fc5";
    $url   = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages";
    $data  = array(
        'From' => $from,
        'To' => $to,
        'Body' => $body
    );
    $post  = http_build_query($data);
    $x     = curl_init($url);
    curl_setopt($x, CURLOPT_POST, true);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
    curl_setopt($x, CURLOPT_POSTFIELDS, $post);
    $y = curl_exec($x);
    curl_close($x);
    //var_dump($post);
    //var_dump($y);
    return $y;
}

?>