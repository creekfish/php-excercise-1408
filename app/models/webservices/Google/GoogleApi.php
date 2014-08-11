<?php

namespace Creekfish\Models\Webservices\Google\Maps;


use Creekfish\Models\Coordinate;
use Creekfish\Models\Webservices\RestApiDriverInterface;
use Creekfish\Models\Webservices\RestApiInterface;
use Creekfish\Models\Webservices\RestApiResponseType;

/**
 * Class GoogleMapsApi
 * @package Creekfish\Models\Webservices\Google\Maps
 *
 * Base class for all Google api's
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
abstract class GoogleApi extends RestApiAbstract {

    /**
     * @return string
     */
    public function getApiHost() {
        return 'http://maps.googleapis.com';
    }

    /**
     * @return array<RestApiResponseType>
     */
    public function getAllowedResponseTypes() {
        return array(
            RestApiResponseType::JSON,
            RestApiResponseType::XML
        );
    }

}