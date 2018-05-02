<?php
namespace TeamAlpha\Web;
error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING );
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/auth.php';

$dbconfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/config.ini');
$host=$dbconfig['db_server'];
$db=$dbconfig['db_name'];
$user=$dbconfig['db_user'];
$pass=$dbconfig['db_password'];
$conn=mysqli_connect("$host","$user","$pass","$db");
// Declare use on objects to be used
use Exception;
use PDOException;
if (!Auth::Authenticate()) {
    Http::ReturnError(401, array('message' => 'Invalid API Key provided.'));    
    return;
}
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
$hashed=password_hash($password, PASSWORD_DEFAULT);
$mobile=$vals->mobile;
//check value
$checkexisting=mysqli_query($conn, "SELECT id,email,mobile,password FROM passenger WHERE email LIKE '$email' 
|| mobile LIKE '$mobile' LIMIT 1");

if(mysqli_num_rows($checkexisting)>0)
{
	$rv=mysqli_fetch_array($checkexisting);
	$id=$rv['id'];
	$pass=$rv['password'];
	 if(password_verify($password, $pass)) {
                header('HTTP/1.1 200 OK');
	    echo json_encode(array('message' => 'Successfully Authenticated the account ','id' => $id));
                } else {
                    Http::ReturnError(401, array('message' => 'Invalid email / mobile and password.'));
                }
	
}
else{
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Invalid Login Credentials'));
}

}

?>
