<?php
namespace TeamAlpha\Web;

// Require classes
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/http.php';

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
        // Check nearest available vehicle using Haversine Formula: https://stackoverflow.com/questions/574691/mysql-great-circle-distance-haversine-formula
        $db = new Db('SELECT id,
                            ( 6371 * acos( cos( radians(:sourcelat) ) * cos( radians(locationlat) )
                            * cos( radians(locationlong) - radians(:sourcelong) ) + sin( radians(:sourcelat) )
                            * sin(radians(locationlat)) ) ) AS distance
                        FROM `vehicle`
                        WHERE active = 1 AND available = 1
                        HAVING distance < :radius
                        ORDER BY distance
                        LIMIT 1;');

        // Bind parameters
        $db->bindParam(':sourcelat', property_exists($input, 'sourcelat') ? $input->sourcelat : 0);
        $db->bindParam(':sourcelong', property_exists($input, 'sourcelong') ? $input->sourcelong : 0);
        $radius = property_exists($input, 'radius') ? $input->radius : 20;
        $db->bindParam(':radius', $radius);

        $messageSuffix = '';
        $vehicleid = null;
        // Execute
        if ($db->execute() === 0) {
            $messageSuffix = ' But no available vehicle was found within ' . $radius . ' km radius. Your trip will stay as requested until an administrator assigns a vehicle to it, or until you cancel.';
        } else {
            $record = $db->fetchAll()[0];
            $vehicleid = (int) $record['id'];
        }
        // Create Db object
        $db = new Db('INSERT INTO `trip` (vehicleid, passengerid, source, sourcelat, sourcelong, destination, destinationlat, destinationlong, stage, datecreated, datemodified)
        VALUES (:vehicleid, :passengerid, :source, :sourcelat, :sourcelong, :destination, :destinationlat, :destinationlong, :stage, :datecreated, :datemodified)');

        // Bind parameters
        $db->bindParam(':vehicleid', $vehicleid);
        $db->bindParam(':passengerid', property_exists($input, 'passengerid') ? $input->passengerid : null);
        $db->bindParam(':source', property_exists($input, 'source') ? $input->source : null);
        $db->bindParam(':sourcelat', property_exists($input, 'sourcelat') ? $input->sourcelat : null);
        $db->bindParam(':sourcelong', property_exists($input, 'sourcelong') ? $input->sourcelong : null);
        $db->bindParam(':destination', property_exists($input, 'destination') ? $input->destination : null);
        $db->bindParam(':destinationlat', property_exists($input, 'destinationlat') ? $input->destinationlat : null);
        $db->bindParam(':destinationlong', property_exists($input, 'destinationlong') ? $input->destinationlong : null);
        $db->bindParam(':stage', 'Requested');
        $db->bindParam(':photo', property_exists($input, 'photo') ? $input->photo : null);
        $db->bindParam(':datecreated', date('Y-m-d H:i:s'));
        $db->bindParam(':datemodified', date('Y-m-d H:i:s'));

        // Execute and get id
        $db->execute();
        $id = $db->lastInsertId();

        // Commit transaction
        $db->commit();

        // Reply with successful response
        Http::ReturnSuccess(array('message' => 'Trip requested.' . $messageSuffix, 'id' => (int) $id));
    } catch (PDOException $pe) {
        Db::ReturnDbError($pe);
    } catch (Exception $e) {
        Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
    }
}
