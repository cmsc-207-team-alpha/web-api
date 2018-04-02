# Fare API
The Fare API contains operations for managing the fare matrix. Using this API, fare could be set based on vehicle type, distance travel per minute and per kilometer. The API also have an operation for calculating fare based on given parameter.

<details><summary>Registering a new driver</summary>

## Registering a new driver:

### ENDPOINT
`[website base address]/api/fare/add.php`

### REQUEST DETAILS

#### Request Method:
`POST`

#### Request Body:
|Member|Data Type|Comment|
|--|--|--|
|vehicle_type|string||
|base_fare|decimal||
|per_km|decimal||
|per_minute|decimal||

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
|id|numeric|The fare matrix id. Present only if operation is successful|

### SAMPLES

#### Sample Request:
~~~~
POST [website base address]/api/fare/add.php HTTP/1.1
Content-Type: application/json

{
    "vehicle_type":"Sedan",
	"base_fare":"150.00",
	"per_km":"10.00",
	"per_minute":"5.00"
} 
~~~~

#### Sample Response:
~~~~
Access-Control-Allow-Methods: POST
Access-Control-Allow-Orgin: *
Connection: close
Content-Type: application/json; charset=UTF-8
Date: Sun, 25 Mar 2018 11:14:22 +0000
Location: /api/fare/get.php?id=1
Status: 201

{
    "message": "Fare Matrix Added.",
    "id": 1
}
~~~~


</details>


<details><summary>Getting the fare matrix (Detailed Response)</summary>

## Getting the fare matrix (Detailed Response):

### ENDPOINT
`[website base address]/api/fare/get.php`

### REQUEST DETAILS

#### Request Method:
`GET`

#### Request Parameter:
|Name|Description|
|--|--|
|id|Id of the fare matrix|

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
|vehicle_type|string||
|base_fare|decimal||
|per_km|decimal||
|per_minute|decimal||

### SAMPLES

#### Sample Request:
~~~~
GET [website base address]/api/fare/get.php?id=1 HTTP/1.1 
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
    "vehicle_type":"Sedan",
	"base_fare":"150.00",
	"per_km":"10.00",
	"per_minute":"5.00"
}
~~~~

</details>

<details><summary>Getting fare matrix list</summary>

## Getting fare matrix list:

### ENDPOINT
`[website base address]/api/fare/get.php`

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
|vehicle_type|string||
|base_fare|decimal||
|per_km|decimal||
|per_minute|decimal||

### SAMPLES

#### Sample Request:
~~~~
GET [website base address]/api/fare/get.php HTTP/1.1 
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
        "vehicle_type":"Sedan",
        "base_fare":"150.00",
        "per_km":"10.00",
        "per_minute":"5.00"
    },
    {
        "id": 5,
        "vehicle_type":"Limousine",
        "base_fare":"300.00",
        "per_km":"15.00",
        "per_minute":"6.00"
    }
]
~~~~


</details>

<details><summary>Updating the fare matrix</summary>

## Updating the fare matrix:

### ENDPOINT
`[website base address]/api/fare/update.php`

### REQUEST DETAILS

#### Request Method:
`POST`

#### Request Body:
|Member|Data Type|Comment|
|--|--|--|
|id|numeric||
|vehicle_type|string||
|base_fare|decimal||
|per_km|decimal||
|per_minute|decimal||

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
POST [website base address]/api/fare/update.php HTTP/1.1
Content-Type: application/json

{
    "id": 1,
    "vehicle_type":"Sedan",
    "base_fare":"150.00",
    "per_km":"10.00",
    "per_minute":"5.00"
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
    "message": "Fare matrix updated.",
    "id": 1
}
~~~~


</details>

<details><summary>Deleting the fare matrix</summary>

## Deleting the fare matrix:

### ENDPOINT
`[website base address]/api/fare/delete.php`

### REQUEST DETAILS

#### Request Method:
`POST`

#### Request Body:
|Member|Data Type|Comment|
|--|--|--|
|id|numeric||

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
POST [website base address]/api/fare/delete.php HTTP/1.1
Content-Type: application/json

{
    "id": 1
}
~~~~

#### Sample Response:
~~~~
Access-Control-Allow-Methods: POST
Access-Control-Allow-Orgin: *
Connection: keep-alive
Content-Length: 51
Content-Type: application/json; charset=UTF-8
Date: Sat, 24 Mar 2018 11:35:09 GMT
Status: 200

{
    "message": "Fare matrix successfully deleted.",
    "id": 1
}
~~~~


</details>

<details><summary>Calculating fare</summary>

## Calculating fare:

### ENDPOINT
`[website base address]/api/fare/compute.php`

### REQUEST DETAILS

#### Request Method:
`POST`

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
**Array of:**

|Member|Data Type|Comment|
|--|--|--|
|vehicle_type|string||
|distance_km|decimal|Travel distance in kilometer|
|distance_minute|decimal|Travel time in minute|

### SAMPLES

#### Sample Request:
~~~~
POST [website base address]/api/fare/compute.php HTTP/1.1
Content-Type: application/json

{
    "vehicle_type":"Sedan",
    "distance_km": "12.56",
    "distance_minute": "96.8"
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
    "Vehicle Type": "Sedan",
    "Base Fare": "150.00",
    "Per KM": "10.00",
    "Per Minute": "5.00",
    "Distance": 12.56,
    "Total Amount": 759.6
}
~~~~


</details>