<?php

namespace Creekfish\Models;

/**
 * Class ModelAbstract
 * @package Creekfish\Models
 *
 * Default "base" implementations for basic model functionality.
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
abstract class ModelAbstract implements ModelInterface {
    /**
     * Return JSON representation of the model.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Populate the model from a JSON representation.
     *
     * @param string $json
     */
    public function fromJson($json)
    {
        $jsonArray = json_decode($json);
        if (is_object($jsonArray)) {
            $jsonArray = get_object_vars($jsonArray);
        }
        $this->fromArray($jsonArray);
    }
}