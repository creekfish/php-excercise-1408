<?php

namespace Creekfish\Models;

/**
 * Class ModelInterface
 * @package Creekfish\Models
 *
 * Define basic functionality for all models beyond
 * what Laravel Eloquent ORM provides
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
interface ModelInterface {
    /**
     * @return array
     */
    public function toArray();

    /**
     * @param array $data
     * @return void
     */
    public function fromArray(array $data);

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0);

    /**
     * @param string $jsonArray
     */
    public function fromJson($jsonArray);
}