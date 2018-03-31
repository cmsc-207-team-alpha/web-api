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
    Http::ReturnError(400, array('message' => 'Administrator details are empty.'));
} else {
    if ((!property_exists($input, 'email') && !property_exists($input, 'mobile')) || !property_exists($input, 'password')) {        
        Http::ReturnError(400, array('message' => 'Email / mobile or password was not provided.'));
        return;
    }

    try {
        // Create Db object
        $db = new Db(property_exists($input, 'email') ? 'SELECT * FROM `admin` WHERE email = :email LIMIT 1' : 'SELECT * FROM `admin` WHERE mobile = :mobile LIMIT 1');

        // Bind parameters
        if (property_exists($input, 'email')) {
            $db->bindParam(':email', property_exists($input, 'email') ? $input->email :null);
        } else {
            $db->bindParam(':mobile', property_exists($input, 'mobile') ? $input->mobile : null);
        }

        // Execute
        if ($db->execute() === 0) {
            Http::ReturnError(404, array('message' => 'Administrator not found.'));
        } else {            
            // Administrator was found
            $record = $db->fetchAll()[0];
            if ((int) $record['active'] === 0) {
                Http::ReturnError(401, array('message' => 'Account is inactive.'));
            } else {
                if(password_verify($input->password, $record['password'])) {
                    Http::ReturnSuccess(array('message' => 'Authentication success.', 'id' => $record['id']));
                } else {
                    Http::ReturnError(401, array('message' => 'Invalid email / mobile and password.'));
                }
            }
        }
    } catch (PDOException $pe) {
        Db::ReturnDbError($pe);
    } catch (Exception $e) {
        Http::ReturnError(500, array('message' => 'Server error: ' . $e->getMessage() . '.'));
    }
}
