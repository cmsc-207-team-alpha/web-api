# Passenger API

Registration for New Passengers:
URL: [webaddress]/api/passenger/create.php
REQUEST METHOD: POST
Notes: Email/mobilenumber/panicnumber cannot be reused thus will return an error message.

Update Existing Passenger
URL: [webaddress]/api/passenger/update.php
REQUEST METHOD: POST
Notes:
->id of the user will be required in the json file to be forwarded.
->Currently all fields can be updated. except the datecreated and other status based fields.   


Delete Existing Passenger
URL: [webaddress]/api/passenger/delete.php
REQUEST METHOD: POST
Notes: 
->id of the user to be deleted will be required in the json file to be forwarded.
->Account/ row will be removed from the database

