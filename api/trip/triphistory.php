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

$id = 0;
$vehicleid = 0;
$stage = '';


// Extract request query string
if (array_key_exists('id', $_GET)) {
    $id = intval($_GET['id']);
}
if (array_key_exists('vehicleid', $_GET)) {
    $vehicleid = intval($_GET['vehicleid']);
}
if (array_key_exists('stage', $_GET)) {
    $stage = $_GET['stage'];
}


if ($id === 0 && $vehicleid === 0 && $stage === '') {
    Http::ReturnError(400, array('message' => 'Trip id or trip stage was not provided.'));
    return;
}


try {
	if ($id === 0 && vehicleid ===0) {
	
    // Create Db object
    $db = new Db('SELECT t.*, p.firstname passengerfirstname, p.lastname passengerlastname, v.plateno, v.type, v.make, v.model, v.color, d.firstname driverfirstname, d.lastname driverlastname 
	FROM trip t
    (INNER JOIN passenger p ON t.passengerid = p.id
    LEFT JOIN vehicle v ON t.vehicleid = v.id
    LEFT JOIN driver d ON v.driverid = d.id'));
	
		
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
	}
	
} catch (PDOException $pe) {
    Db::ReturnDbError($pe);
} catch (Exception $e) {
    Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
}