<?php
namespace TeamAlpha\Web;

// Require classes
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/http.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/models/trip.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/models/triplistitem.php';

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
$passengerid = 0;

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

if (array_key_exists('passengerid', $_GET)) {
    $stage = $_GET['passengerid'];
}

if ($id === 0 && $stage === '') {
    Http::ReturnError(400, array('message' => 'Trip id, Passenger id or trip stage was not provided.'));
    return;
}

try {
    
        // Create Db object
        $db = new Db('SELECT * FROM `trip` WHERE id = :id LIMIT 1');
        // Bind parameters
        $db->bindParam(':id', $id);

        // Execute
        if ($db->execute() === 0) {
            Http::ReturnError(404, array('message' => 'Trip not found.'));
        } else {
            // Driver document was found
            $record = $db->fetchAll()[0];
            $trip = new Trip($record);

            // Reply with successful response
            Http::ReturnSuccess($trip);
        }
    }
} catch (PDOException $pe) {
    Db::ReturnDbError($pe);
} catch (Exception $e) {
    Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
}
