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
    Http::ReturnError(400, array('message' => 'Driver details are empty.'));
} else {
    try {
        // Create Db object
        $db = new Db('INSERT INTO `driver` (firstname, lastname, email, password, address, mobile, active, verified, blocked, token, photo, datecreated, datemodified)
                                VALUES (:firstname, :lastname, :email, :password, :address, :mobile, :active, :verified, :blocked, :token, :photo, :datecreated, :datemodified)');

        // Bind parameters
        $db->bindParam(':firstname', property_exists($input, 'firstname') ? $input->firstname : null);
        $db->bindParam(':lastname', property_exists($input, 'lastname') ? $input->lastname : null);
        $db->bindParam(':email', property_exists($input, 'email') ? $input->email : null);
        $db->bindParam(':password', property_exists($input, 'password') ? password_hash($input->password, PASSWORD_DEFAULT) : null);
        $db->bindParam(':address', property_exists($input, 'address') ? $input->address : null);
        $db->bindParam(':mobile', property_exists($input, 'mobile') ? $input->mobile : null);
        $db->bindParam(':active', 0);
        $db->bindParam(':verified', 0);
        $db->bindParam(':blocked', 0);
        $db->bindParam(':token', null);
        $db->bindParam(':photo', property_exists($input, 'photo') ? $input->photo : null);
        $db->bindParam(':datecreated', date('Y-m-d H:i:s'));
        $db->bindParam(':datemodified', date('Y-m-d H:i:s'));

        // Execute and get id
        $db->execute();
        $id = $db->lastInsertId();

        // Commit transaction
        $db->commit();

        // Reply with successful response
        Http::ReturnCreated('/api/driver/get.php?id=' . $id, array('message' => 'Driver registered.', 'id' => (int) $id));
    } catch (PDOException $pe) {
        Db::ReturnDbError($pe);
    } catch (Exception $e) {
        Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
    }
}
