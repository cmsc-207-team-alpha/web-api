# Admin API
Admin API contains operations for manipulating Administrator information including registering a new one, listing all administrators, updating them.


## Registering a new administrator:

### ENDPOINT
`[website base address]/api/admin/register.php`

### REQUEST DETAILS

#### Request Method:
`POST`

#### Request Body:
|Member|Data Type|Comment|
|--|--|--|
|firstname|string||
|lastname|string||
|email|string||
|password|string|

### RESPONSE DETAILS

#### Response Status Codes:
|Status|Description|
|--|--|
|201|Created|
|400|Bad Request|
|405|Method Not Allowed|
|500|Internal Server Error|

#### Response Body:
|Member|Data Type|Comment|
|--|--|--|
|message|string||
|id|numeric|Present only if operation is successful|

### SAMPLES

#### Sample Request:
~~~~
POST [website base address]/api/admin/register.php HTTP/1.1
Content-Type: application/json

{
    "firstname": "Juan",
    "lastname": "Dela Cruz",
    "email": "juan@delacruz.com",
    "password": "meh",
} 
~~~~

#### Sample Response:
~~~~
Access-Control-Allow-Methods: POST
Access-Control-Allow-Orgin: *
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 25 Mar 2018 11:14:22 +0000
Location: /api/admin/get.php?id=1
Status: 201

{
    "message": "Administrator registered.",
    "id": 1
}
~~~~


## Getting an Administrator (Detailed Response):

### ENDPOINT
`[website base address]/api/admin/get.php`

### REQUEST DETAILS

#### Request Method:
`GET`

#### Request Parameter:
|Name|Description|
|--|--|
|id|Id of the Administrator|

### RESPONSE DETAILS

#### Response Status Codes:
|Status|Description|
|--|--|
|200|Success|
|404|Not Found|
|405|Method Not Allowed|
|500|Internal Server Error|

#### Response Body:
|Member|Data Type|Comment|
|--|--|--|
|id |numeric||
|firstname|string||
|lastname|string||
|email|string||
|active|numeric|1 or 0|
|verified|numeric|1 or 0|
|blocked|numeric|1 or 0|

### SAMPLES

#### Sample Request:
~~~~
GET [website base address]/api/admin/get.php?id=1 HTTP/1.1 
~~~~

#### Sample Response:
~~~~
Access-Control-Allow-Methods: GET
Access-Control-Allow-Orgin: *
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 25 Mar 2018 12:59:31 +0000
Status: 200

{
    "id": 1,
    "firstname": "Juan",
    "lastname": "Dela Cruz",
    "email": "juan@delacruz.com",
    "active": 0,
    "verified": 0,
    "blocked": 0,
}
~~~~


## Getting administrators list:

### ENDPOINT
`[website base address]/api/admin/get.php`

### REQUEST DETAILS

#### Request Method:
`GET`

### RESPONSE DETAILS

#### Response Status Codes:
|Status|Description|
|--|--|
|200|Success|
|405|Method Not Allowed|
|500|Internal Server Error|

#### Response Body:
**Array of:**

|Member|Data Type|Comment|
|--|--|--|
|id |numeric||
|firstname|string||
|lastname|string||
|email|string||
|active|numeric|1 or 0|
|verified|numeric|1 or 0|
|blocked|numeric|1 or 0|

### SAMPLES

#### Sample Request:
~~~~
GET [website base address]/api/admin/get.php HTTP/1.1 
~~~~

#### Sample Response:
~~~~
Access-Control-Allow-Methods: GET
Access-Control-Allow-Orgin: *
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 25 Mar 2018 13:04:16 +0000
Status: 200

[
    {
        "id": 1,
        "firstname": "Juan",
        "lastname": "Dela Cruz",
        "email": "juan@delacruz.com",
        "active": 0,
        "verified": 0,
        "blocked": 0
    },
    {
        "id": 8,
        "firstname": "John",
        "lastname": "Doe",
        "email": "john@doe.com",
        "active": 0,
        "verified": 0,
        "blocked": 0
    }
]
~~~~


## Updating an administrator:

### ENDPOINT
`[website base address]/api/admin/update.php`

### REQUEST DETAILS

#### Request Method:
`POST`

#### Request Body:
|Member|Data Type|Comment|
|--|--|--|
|id|numeric||
|firstname|string||
|lastname|string||
|email|string||
|password|string|


### RESPONSE DETAILS

#### Response Status Codes:
|Status|Description|
|--|--|
|200|Success|
|400|Bad Request|
|404|Not Found|
|405|Method Not Allowed|
|500|Internal Server Error|

#### Response Body:
|Member|Data Type|Comment|
|--|--|--|
|message|string||
|id|numeric|Present only if operation is successful|

### SAMPLES

#### Sample Request:
~~~~
POST [website base address]/api/admin/update.php HTTP/1.1
Content-Type: application/json

{
    "id": 1,
    "firstname": "John",
    "lastname": "Doe",
    "email": "john@doe.com",
    "password": "meh"
}
~~~~

#### Sample Response:
~~~~
Access-Control-Allow-Methods: POST
Access-Control-Allow-Orgin: *
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 25 Mar 2018 23:47:16 +0000
Status: 200

{
    "message": "Administrator updated.",
    "id": 1
}
~~~~


## Authenticating an administrator's credentials:

### ENDPOINT
`[website base address]/api/admin/authenticate.php`

### REQUEST DETAILS

#### Request Method:
`POST`

#### Request Body:
|Member|Data Type|Comment|
|--|--|--|
|email|string|Mutually exclusive with mobile|
|password|string||

Note: Email should be present in the request, along with the password.

### RESPONSE DETAILS

#### Response Status Codes:
|Status|Description|
|--|--|
|200|Success|
|400|Bad Request|
|401|Unauthorized|
|404|Not Found|
|405|Method Not Allowed|
|500|Internal Server Error|

#### Response Body:
|Member|Data Type|Comment|
|--|--|--|
|message|string||
|id|numeric|Administrator's id. Present only if operation is successful|

### SAMPLES

#### Sample Request:
~~~~
POST [website base address]/api/admin/authenticate.php HTTP/1.1
Content-Type: application/json

{
	"email": "juan@delacruz.com",
	"password": "meh"
}
~~~~

#### Sample Response:
~~~~
Access-Control-Allow-Methods: POST
Access-Control-Allow-Orgin: *
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Mon, 26 Mar 2018 04:35:21 +0000
Status: 200

{
    "message": "Authentication success.",
    "id": "1"
}
~~~~