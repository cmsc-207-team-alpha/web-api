<?php
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
$checkexisting=mysqli_query($conn, "SELECT email FROM passenger WHERE email LIKE '$email' LIMIT 1");
if(mysqli_num_rows($checkexisting)>0)
{
	$rv=mysqli_fetch_array($checkexisting);
	$rand = substr(md5(microtime()),rand(0,26),6);
	
	$updatepass=mysqli_query($conn,"UPDATE passenger SET password='$rand' WHERE id = $rv[id] LIMIT 1");
	$body ="Use this temporary password to update your password: $rand";
	$email = $email;
	$subject="Forgot password Temporary Password";
	include("..\vendor\phpmailer\phpmailer\src\PHPMailer.php");
	 header('HTTP/1.1 201 Created');
    echo json_encode(array('message' => 'Sent a temporary Password to be used'));
}
else{
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Invalid Email Credentials'));
}

}

?>