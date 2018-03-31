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
$password=$vals->password;
$mobile=$vals->mobile;
//check value
$checkexisting=mysqli_query($conn, "SELECT email,mobile,password FROM passenger WHERE (email LIKE '$email' 
|| mobile LIKE '$mobile') && password LIKE '$password'");
if(mysqli_num_rows($checkexisting)>0)
{
	 header('HTTP/1.1 201 Created');
    echo json_encode(array('message' => 'Successfully Logged In the account'));
}
else{
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Invalid Login Credentials'));
}

}

?>
