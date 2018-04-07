<?php
error_reporting( E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING );
$dbconfig = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/config/config.ini');
$host=$dbconfig['db_server'];
$db=$dbconfig['db_name'];
$user=$dbconfig['db_user'];
$pass=$dbconfig['db_password'];
$conn=mysqli_connect("$host","$user","$pass","$db");



if(!isset($_GET['id']))
{
	$get=mysqli_query($conn,"SELECT * FROM passenger");
	 $response = array();			
	while($sp=mysqli_fetch_array($get))
	{
		
		$passenger->firstname = "$sp[firstname]";
		$passenger->lastname = "$sp[lastname]";
		$passenger->mobile = "$sp[mobile]";
		$passenger->panicmobile = "$sp[panicmobile]";
		$passenger->address = "$sp[address]";
		$passenger->email = "$sp[email]";
		$passenger->password = "$sp[password]";
		array_push($response, $passenger);
		
	}
	echo json_encode($response);
	header('HTTP/1.1 201 Request Success');
}
else{
	
$id=$_GET['id'];
//Check query
$get=mysqli_query($conn,"SELECT * FROM passenger WHERE id = $id LIMIT 1");

if(mysqli_num_rows($get)>0)
{
		$rv=mysqli_fetch_array($get);
		$passenger="";
		$passenger->firstname = "$rv[firstname]";
		$passenger->lastname = "$rv[lastname]";
		$passenger->mobile = "$rv[mobile]";
		$passenger->panicmobile = "$rv[panicmobile]";
		$passenger->address = "$rv[address]";
		$passenger->email = "$rv[email]";
		$passenger->password = "$rv[password]";
		
		
		header('HTTP/1.1 200 OK');
		echo json_encode($passenger);
}
else
{
	header('HTTP/1.1 400 Bad Request');
    echo json_encode(array('message' => 'Query Error'));
	
}

	
}
?>
