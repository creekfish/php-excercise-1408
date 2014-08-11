<?php

namespace Creekfish\Models\Webservices\Google\Maps;

use Creekfish\Models\Coordinate;
use Creekfish\Models\Webservices\RestApiDriverInterface;
use Creekfish\Models\Webservices\RestApiInterface;
use Creekfish\Models\Webservices\RestApiResponseType;

/**
 * Class RestApiAbstract
 * @package Creekfish\Models\Webservices
 *
 * Base class for all REST api's
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
abstract class RestApiAbstract implements RestApiInterface {

    /**
     * @var RestApiDriverInterface
     */
    private $driver;

    /**
     * @param RestApiDriverInterface $driver
     */
    public function __construct(RestApiDriverInterface $driver) {
        $this->driver = $driver;
    }

    /**
     * Return true if response type is allowed for this api.
     *
     * @param RestApiResponseType $responseType
     * @return boolean
     */
    public function isAllowedResponseType(RestApiResponseType $responseType) {
        return in_array($responseType, $this->getAllowedResponseTypes());
    }

    /**
     * Return the driver instance for this api
     *
     * @return RestApiDriverInterface
     */
    public function getDriver() {
        return $this->driver;
    }

    /**
     * Return array of Rest Api response types allowed for this api.
     *
     * @return array<RestApiResponseType>
     */
    abstract protected function getAllowedResponseTypes();

}