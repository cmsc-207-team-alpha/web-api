<?php
namespace TeamAlpha\Web;

class FareList
{
    public $id;
    public $vehicle_type;
    public $base_fare;
    public $per_km;
    public $per_minute;

    public function __construct(array $data)
    {
        if ($data !== null) {
            $this->id = (int) $data['id'] ?? 0;
            $this->vehicle_type = $data['vehicle_type'] ?? null;
            $this->base_fare = $data['base_fare'] ?? null;
            $this->per_km = $data['per_km'] ?? null;
            $this->per_minute = $data['per_minute'] ?? null;
        }
    }
}
