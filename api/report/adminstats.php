<?php
namespace TeamAlpha\Web;

// Require classes
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/utils/http.php';
require $_SERVER['DOCUMENT_ROOT'] . '/api/models/adminstats.php';

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
    $active = array_key_exists('active', $_GET) ? $_GET['active'] . '1';

    // Create Db object
    $db = new Db('SELECT
            (SELECT COUNT(id) FROM `admin`) totaladmin,
            (SELECT COUNT(id) FROM `admin` WHERE stage = \'Active\' AND active) totalactive');


    // Execute query
    $db->execute();

    // Extract result
    $record = $db->fetchAll()[0];
    $adminstats = new AdminStats($record);

    // Reply with successful response
    Http::ReturnSuccess($tripstats);
} catch (PDOException $pe) {
    Db::ReturnDbError($pe);
} catch (Exception $e) {
    Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
}
