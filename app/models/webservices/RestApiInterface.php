<?php

namespace Creekfish\Models\Webservices;

/**
 * Class RestApiInterface
 * @package Creekfish\Models\Webservices
 *
 * Interface for a Restful webservice api
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
interface RestApiInterface extends ApiInterface {

    /**
     * Return the driver instance for this api
     *
     * @return RestApiDriverInterface
     */
    public function getDriver();

}