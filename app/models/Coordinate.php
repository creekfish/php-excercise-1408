<?php

namespace Creekfish\Models;

/**
 * Class Coordinate
 * @package Creekfish\Models
 *
 * Coordinate model with a specific array/json mapping:
 * { lat: X, lon: Y, elevation: E }
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class Coordinate extends ModelAbstract {

    /**
     * @var float
     */
    private $lat;

    /**
     * @var float
     */
    private $lon;

    /**
     * @var float
     */
    private $elevation;

    function __construct($lat = null, $lon = null, $elevation = null)
    {
        if (isset($lat)) {
            $this->lat = $lat;
        }
        if (isset($lon)) {
            $this->lon = $lon;
        }
        if (isset($elevation)) {
            $this->elevation = $elevation;
        }
    }

    /**
     * @return array
     */
    public function toArray() {
        return array('lat' => $this->lat, 'lon' => $this->lon, 'elevation' => $this->elevation);
    }

    /**
     * Return a JSON compatible value.
     * @return array
     */
    public function toJsonValue() {
        return $this->toArray();
    }

    /**
     * @param array $data
     */
    public function fromArray(array $data) {

        if (isset($data['lat'])) {
            $this->setLat($data['lat']);
        }

        if (isset($data['lon'])) {
            $this->setLon($data['lon']);
        } else if (isset($data['lng'])) {
            $this->setLon($data['lng']);
        }

        if (isset($data['elevation'])) {
            $this->setElevation($data['elevation']);
        }
    }

    /**
     * @param float $elevation
     * @throws \InvalidArgumentException
     */
    public function setElevation($elevation)
    {
        if (!is_numeric($elevation)) {
            throw new \InvalidArgumentException('Elevation must be numeric value.');
        }
        $this->elevation = (float) $elevation;
    }

    /**
     * @return float
     */
    public function getElevation()
    {
        return $this->elevation;
    }

    /**
     * @param float $lat
     * @throws \InvalidArgumentException
     */
    public function setLat($lat)
    {
        if (!is_numeric($lat)) {
            throw new \InvalidArgumentException('Latitude must be numeric value.');
        }
        $this->lat = (float) $lat;
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lon
     * @throws \InvalidArgumentException
     */
    public function setLon($lon)
    {
        if (!is_numeric($lon)) {
            throw new \InvalidArgumentException('Longitude must be numeric value.');
        }
        $this->lon = (float) $lon;
    }

    /**
     * @return float
     */
    public function getLon()
    {
        return $this->lon;
    }

}