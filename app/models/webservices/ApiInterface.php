<?php

namespace Creekfish\Models\Webservices;

/**
 * Class ApiInterface
 * @package Creekfish\Models\Webservices
 *
 * Interface for any webservice api
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
interface ApiInterface {

    /**
     * Return url for the api host
     *
     * @return string
     */
    public function getApiHost();

    /**
     * Return the base route for the api
     *
     * @return string
     */
    public function getApiRoute();

}