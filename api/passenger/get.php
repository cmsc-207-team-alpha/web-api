<?php
$dbconfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/config.ini');
$host=$dbconfig['db_server'];
$db=$dbconfig['db_name'];
$user=$dbconfig['db_user'];
$pass=$dbconfig['db_password'];
$conn=mysqli_connect("$host","$user","$pass","$db");


if(!isset($_GET['id']))
{
	header('HTTP/1.1 400 Bad Request');
	echo json_encode(array('message' => 'No Value selected'));
	
}
else{
	
$id=$_GET['id'];
//Check query
$get=mysqli_query($conn,"SELECT * FROM passenger WHERE id = $id LIMIT 1");

if(mysqli_num_rows($get)>0)
{
		$rv=mysqli_fetch_array($get);
		$passenger->firstname = "$rv[firstname]";
		$passenger->lastname = "$rv[lastname]";
		$passenger->mobile = "$rv[mobile]";
		$passenger->panicmobile = "$rv[panicmobile]";
		$passenger->address = "$rv[address]";
		$passenger->email = "$rv[email]";
		$passenger->password = "$rv[password]";
		
		
		header('HTTP/1.1 201 Request Success');
		echo json_encode($myObj);
}
else
{
	header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Query Error'));
	
}

	
}


?>
