<?php

namespace Creekfish\Models\Webservices;

use Creekfish\Models\Webservices\Http\HttpMethod;
use Creekfish\Models\Webservices\Http\HttpResponse;


/**
 * Class RestApiDriverInterface
 * @package Creekfish\Models\Webservices
 *
 * Restful HTTP driver interface
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
interface RestApiDriverInterface {

    /**
     * Submit a request to the given resource route using the data and method
     * provided.
     *
     * @param string $resourceRoute URL route to the resource, relative to host root path
     * @param array $data Data to be passed with the request (e.g. in query string or POST data)
     * @param HttpMethod $method HTTP method for the request, GET is default method
     * @param string $host Override default host (optional)
     * @return HttpResponse
     * @throws \InvalidArgumentException
     */
    public function restCall($resourceRoute, array $data = array(), HttpMethod $method = null, $host = null);
}