<?php

namespace Main;

class Clock{
    public int $id;
    public $coords;
    public string $address;
    public float $dist ;
    public $type;
    public $friendlyDist;

    public float $coordX;
    public float $coordY;

    public function setDist(float $dist)
    {
        $this->dist = $dist;
        $this->friendlyDist = ($dist > 1000) ? round($dist/1000, 2)." км" : round($dist, 2)." м";
    }
    public function __construct($id, $address, $coords, $X, $Y, $dist, $type)
    {
        $this->id = $id;
        $this->address = $address;
        $this->coords = $coords;
        $this->coordX = $X;
        $this->coordY = $Y;
        $this->type = $type;

        ($dist != null) ? $this->setDist($dist) : false;
    }
    public static function fromPDORow($row): static
    {
        return new static($row['clock_id'],$row['Location'], $row['Coord'], $row['X'], $row['Y'],$row['dist']?? null, $row['type']);
    }
}
