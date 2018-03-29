<?php
namespace TeamAlpha\Web;

class Trip
{
    public $id;
    public $vehicleId;
    public $passengerId;
    public $source;
    public $sourceLat;
    public $sourceLong;
    public $destination;
    public $destinationLat;
    public $destinationLong;
    public $stage;
    public $datestart;
    public $dateend;
    public $datecreated;
    public $datemodified;

    public function __construct(array $data)
    {
        if ($data !== null) {
            $this->id = (int) $data['id'] ?? 0;
            $this->vehicleId = (int) $data['vehicleId'] ?? 0;
            $this->passengerId = (int) $data['passengerId'] ?? 0;
            $this->source = $data['source'] ?? null;
            $this->sourceLat = (double) $data['sourceLat'] ?? 0;
            $this->sourceLong = (double) $data['sourceLong'] ?? 0;
            $this->destination = $data['destination'] ?? null;
            $this->destinationLat = (double) $data['destinationLat'] ?? 0;
            $this->destinationLong = (double) $data['destinationLong'] ?? 0;
            $this->stage = $data['stage'] ?? null;
            $this->datestart = $data['datestart'] ?? null;
            $this->dateend = $data['dateend'] ?? null;
            $this->datecreated = $data['datecreated'] ?? null;
            $this->datemodified = $data['datemodified'] ?? null;
        }
    }
}
