<?php
error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING );
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
$token=$vals->token;
//check value
$checkexisting=mysqli_query($conn, "SELECT * FROM passenger WHERE token LIKE '$token' LIMIT 1");
if(mysqli_num_rows($checkexisting)>0)
{
	$rv=mysqli_fetch_array($checkexisting);
  $id=$rv['id'];
	
	$updatepass=mysqli_query($conn,"UPDATE passenger SET active = 1 WHERE id = $id LIMIT 1");
  if($updatepass){
	
	 header('HTTP/1.1 200 OK');
    echo json_encode(array('message' => 'Successfully Activated the account: id -> $id'));
    }
    else
    {header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Problem Activating the account'));
    
    }
}
else{
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'No Such token'));
}
}
?>
