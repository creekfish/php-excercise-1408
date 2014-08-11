<?php

namespace Creekfish\Models;

/**
 * Class EloquentModelAbstract
 * @package Creekfish\Models
 *
 * Default "base" implementations for model functionality
 * that extends Eloquent ORM model implementation.
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
abstract class EloquentModelAbstract extends \Eloquent implements ModelInterface {

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @param string $jsonArray
     */
    public function fromJson($jsonArray)
    {
        $this->fromArray(json_decode($jsonArray));
    }

    /**
     * @param array $data
     * @return void
     */
    public function fromArray(array $data)
    {
        // loop attributes to only set ones present in the model
        foreach ($this->getAttributes() as $attr) {
            if (isset($data[$attr])) {
                $this->setAttribute($attr, $data[$attr]);
            }
        }
    }
}