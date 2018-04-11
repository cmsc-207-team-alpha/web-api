<?php
namespace TeamAlpha\Web;
// Require classes
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/http.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/models/triplistitemextended.php';
// Declare use on objects to be used
use Exception;
use PDOException;
// HTTP headers for response
Http::SetDefaultHeaders('GET');
// Check if request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    Http::ReturnError(405, array('message' => 'Request method is not allowed.'));
    return;
}
try {
	
	$datestart = array_key_exists('datestart', $_GET) ? $_GET['datestart'] . ' 00:00:00' : '1000-01-01 00:00:00';
    $dateend = array_key_exists('dateend', $_GET) ? $_GET['dateend'] . ' 23:59:59' : '9999-12-31 23:59:59';
    // Create Db object
    $db = new Db('SELECT t.*, p.firstname passengerfirstname, p.lastname passengerlastname FROM trip t
    INNER JOIN passenger p ON t.passengerid = p.id
    LEFT JOIN vehicle v ON t.vehicleid = v.id
    LEFT JOIN driver d ON v.driverid = d.id WHERE stage LIKE :stage AND datecreated BETWEEN :datestart AND :dateend');
	
		$db->bindParam(':passengerfirstname', $passengerfirstname);
		$db->bindParam(':passengerlastname', $passengerlastname);
	    $db->bindParam(':stage', '%' . $stage . '%');
        $db->bindParam(':datestart', $datestart);
        $db->bindParam(':dateend', $dateend);
        if ($vehicleid !== 0) {
            $db->bindParam(':vehicleid', $vehicleid);
        }
		if ($p.id !== 0) {
            $db->bindParam(':p.id', $p.id);
        }
		if ($v.id !== 0) {
            $db->bindParam(':v.id', $v.id);
        }
		if ($d.id !== 0) {
            $db->bindParam(':d.id', $d.id);
        }
	
    $response = array();
    // Execute
    if ($db->execute() > 0) {
        // Drivers were found
        $records = $db->fetchAll();
        foreach ($records as &$record) {
            $trip = new TripListItemExtended($record);
            array_push($response, $trip);
        }
    }
    // Reply with successful response
    Http::ReturnSuccess($response);
} catch (PDOException $pe) {
    Db::ReturnDbError($pe);
} catch (Exception $e) {
    Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
}