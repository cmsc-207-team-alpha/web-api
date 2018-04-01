<?php
namespace TeamAlpha\Web;

// Require classes
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/http.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/models/farelist.php';

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
    Http::ReturnError(400, array('message' => 'Fare computation details are empty.'));
} else {
    try {
        // Create Db object
        $db = new Db('SELECT * FROM `fare` WHERE vehicle_type = :vehicle_type LIMIT 1');

        // Bind parameters
        $db->bindParam(':vehicle_type', property_exists($input, 'vehicle_type') ? $input->vehicle_type : 0);

        // Execute
        if ($db->execute() === 0) {
            Http::ReturnError(404, array('message' => 'Vehicle type not found.'));
        }
        else {
            $record = $db->fetchAll()[0];
            $vehicletype = $record['vehicle_type'];
            $basefare = $record['base_fare'];
            $perkm = $record['per_km'];
            $perminute = $record['per_minute'];

            $distance = $input->distance_km;
            $time = $input->distance_minute;
            $amount = $basefare + ($distance * $perkm) + ($time * $perminute);

            // //source: https://stackoverflow.com/questions/27928/calculate-distance-between-two-latitude-longitude-points-haversine-formula
            // $lat1 =  $input->sourcelat;
            // $long1 = $input->sourcelong;
            // $lat2 = $input->destinationlat;
            // $long2 = $input->destinationlong;
            // $radius = 6371; //Radius of the earth in KM
            // $dlat = deg2rad($lat2-$lat1);
            // $dlong = deg2rad($long2-$long1);
            // $a = sin($dlat/2) * sin($dlat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlong/2) * sin($dlong/2);
            // $c = 2 * atan2(sqrt($a), sqrt(1-$a)); //angular distance
            // $distance = $radius * $c; //distance in KM
            // $amount = $basefare + ($distance * $perkm);

            Http::ReturnSuccess(array('Vehicle Type' => $vehicletype, 'Base Fare' => $basefare, 'Per KM' => $perkm, 'Distance' => round($distance,2), 'Total Amount' => round($amount,2)));

        }
    } catch (PDOException $pe) {
        Db::ReturnDbError($pe);
    } catch (Exception $e) {
        Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
    }
}