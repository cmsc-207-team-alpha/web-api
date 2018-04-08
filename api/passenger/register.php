<?php
error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING );
// Require classes
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/email.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/http.php';
// Declare use on objects to be used
use Exception;
use PDOException;
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/email.php';
$dbconfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/config.ini');
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
$firstname=$vals->firstname;
$lastname=$vals->lastname;
$email=$vals->email;
$password=$vals->password;
$hashed=password_hash("$password", PASSWORD_DEFAULT);	
$address=$vals->address;
$mobile=$vals->mobile;
$pmobile=$vals->panicmobile;
$datecreated=date("Y-m-d H:i:s");
$ac=$email." ".$lastname;
$token=md5($ac);

$checkexisting=mysqli_query($conn, "SELECT email,mobile,panicmobile FROM passenger WHERE email LIKE '$email'
|| email LIKE '$mobile' || panicmobile LIKE '$pmobile'");
if(mysqli_num_rows($checkexisting)>0)
{
	 header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Email Or mobile used is currently registered try using another.'));
}
else{

if(count(json_decode($data,1))==0) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Passenger details are empty.'));
}
else{
$remail = $email;
	$name="$lastname, $firstname";
	$subject="Account Activation";
	 // Send email
            $htmlbody = 'Hi ' . $email . ',<br/><br/>Here is your Account Activation Token<br/>' . $token . '<br/><br/>Please do Use this token to activate your account.<br/><br/><br/><small>This message was sent by Team Alpha\'s Passenger Forgot Pass.</small>';
            $altbody = 'Hi ' .  $email . ', Here is your Account Activation Token ' . $token . ' Please do Use this token to activate your account. This message was sent by Team Alpha Passenger Forgot Pass.';
            $email = new Email();
            $email->send($remail, $name, 'Temporary Password sent', $htmlbody, $altbody);
	
//Insert Query
$qrys=mysqli_query($conn, "INSERT INTO passenger VALUES('','$firstname','$lastname',
'$email','$hashed','$address','$mobile','$panicmobile',
'0','0','0','$token'
,'','$datecreated','')")or die("sql error");

	if(!$qrys)
	{
		header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Query Error'));
		
	}
	else
	{
	header('HTTP/1.1 201 Created');
    echo json_encode(array('message' => 'Passenger record created.'));
	}
}
}
}
?>
