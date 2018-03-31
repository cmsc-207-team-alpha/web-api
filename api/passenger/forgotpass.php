<?php
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/email.php';
$dbconfig = parse_ini_file("../config/config.ini");
$host=$dbconfig['db_server'];
$db=$dbconfig['db_name'];
$user=$dbconfig['db_user'];
$pass=$dbconfig['db_password'];
$conn=mysqli_connect("$host","$user","$pass","$db");


$url = 'php://input'; // path to your JSON file
$data = file_get_contents($url); // put the contents of the file into a variable
$vals = json_decode($data); // decode the JSON feed

if(is_null($vals))
{
	 header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'No contents to process'));
	
}
else{
$email=$vals->email;

//check value
$checkexisting=mysqli_query($conn, "SELECT email,firstname,lastname FROM passenger WHERE email LIKE '$email' LIMIT 1");
if(mysqli_num_rows($checkexisting)>0)
{
	$rv=mysqli_fetch_array($checkexisting);
	$rand = substr(md5(microtime()),rand(0,26),6);
	$name="$rv[lastname], $rv[firstname]";
	$updatepass=mysqli_query($conn,"UPDATE passenger SET password='$rand' WHERE id = $rv[id] LIMIT 1");
	$body ="Use this temporary password to update your password: $rand";
	$remail = $email;
	$subject="Forgot password Temporary Password";
	 // Send email
            $htmlbody = 'Hi ' . $email . ',<br/><br/>Here is your forgot Password Token<br/>' . $rand . '<br/><br/>Please do Use this temporary password to log in your account!<br/><br/><br/><small>This message was sent by Team Alpha\'s Passenger Forgot Pass.</small>';
            $altbody = 'Hi ' .  $email . ', Here is your forgot Password Token: ' . $rand . ' Please do Use this temporary password to log in your account!This message was sent by Team Alpha Passenger Forgot Pass.';
            $email = new Email();
            $email->send($remail, $name, 'Temporary Password sent', $htmlbody, $altbody);

	 header('HTTP/1.1 201 Created');
    echo json_encode(array('message' => 'Sent a temporary Password to be used'));
}
else{
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Invalid Email Credentials'));
}

}

?>
