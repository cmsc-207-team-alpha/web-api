<?php
namespace TeamAlpha\Web;

// Require classes
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/email.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/http.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/models/trip.php';

// Declare use on objects to be used
use Exception;
use PDOException;

// Set default response headers
Http::SetDefaultHeaders('POST');

// Check if request method is correct
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Http::ReturnError(405, array('message' => 'Request method is not allowed.'));
    return;
}

// Extract request body
$input = json_decode(file_get_contents("php://input"));

if (is_null($input)) {
    Http::ReturnError(400, array('message' => 'Trip details are empty.'));
} else {
    try {
        // Create Db object
        $db = new Db('SELECT * FROM `trip` WHERE id = :id LIMIT 1');

        // Bind parameters
        $db->bindParam(':id', property_exists($input, 'id') ? $input->id : 0);

        // Execute
        if ($db->execute() === 0) {
            Http::ReturnError(404, array('message' => 'Trip not found.'));
        } else {
            // Trip  was found
            $record = $db->fetchAll()[0];
            $trip = new Trip($record);

            // Create Db object
            $db = new Db('UPDATE `trip` SET stage = :stage, datemodified = :datemodified WHERE id = :id');

            // Bind parameters
            $db->bindParam(':id', property_exists($input, 'id') ? $input->id : 0);
            $db->bindParam(':stage', 'Completed');
            $db->bindParam(':datemodified', date('Y-m-d H:i:s'));

            // Execute
            $db->execute();

            // Commit transaction
            $db->commit();

            // Get passenger
            $db = new Db('SELECT * FROM `passenger` WHERE id = :id LIMIT 1');
            $db->bindParam(':id', $trip->passengerid);
            $db->execute();
            $passenger = $db->fetchAll()[0];

            // Send email
            $htmlbody = 'Hi ' . $passenger['firstname'] . ',<br/><br/>You have just arrived at ' . $trip->destination . '!<br/><br/>Thank you for choosing Team Alpha. For your reference, your trip booking number is <strong>TRIP-' . $trip->id . '</strong>.<br/><br/>Have a great day!<br/><br/><br/><small>This message was sent by Team Alpha\'s Trip Module.</small>';
            $altbody = 'Hi ' . $passenger['firstname'] . ', You have just arrived at ' . $trip->destination . '! Thank you for choosing Team Alpha. For your reference, your trip booking number is TRIP-' . $trip->id . '. Have a great day! This message was sent by Team Alpha\'s Trip Module.';
            $email = new Email();
            $email->send($passenger['email'], $passenger['firstname'], 'Destination reached!', $htmlbody, $altbody);

            // Reply with successful response
            Http::ReturnSuccess(array('message' => 'Trip ended.', 'id' => $input->id));
        }
    } catch (PDOException $pe) {
        Db::ReturnDbError($pe);
    } catch (Exception $e) {
        Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
    }
}
