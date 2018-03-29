<?php
namespace TeamAlpha\Web;

class Trip
{
    public $id;
    public $vehicleId;
    public $passengerId;
    public $sourceLat;
    public $sourceLong;
    public $destinationLat;
    public $destinationLong;
    public $stage;

    public function __construct(array $data)
    {
        if ($data !== null) {
            $this->id = (int) $data['id'] ?? 0;
            $this->vehicleId = (int) $data['vehicleId'] ?? 0;
            $this->passengerId = (int) $data['passengerId'] ?? 0;
            $this->sourceLat = (double) $data['sourceLat'] ?? 0;
            $this->sourceLong = (double) $data['sourceLong'] ?? 0;
            $this->destinationLat = (double) $data['destinationLat'] ?? 0;
            $this->destinationLong = (double) $data['destinationLong'] ?? 0;
            $this->stage = $data['stage'] ?? null;
        }
    }
}
