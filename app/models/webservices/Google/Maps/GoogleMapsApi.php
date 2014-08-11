<?php

namespace Creekfish\Models\Webservices\Google\Maps;

use Creekfish\Lib\HttpStatusCodes;
use Creekfish\Models\Coordinate;
use Creekfish\Models\Webservices\Http\HttpJsonResponseDecorator;
use Creekfish\Models\Webservices\Http\HttpMethod;
use Creekfish\Models\Webservices\Http\HttpResponse;
use Creekfish\Models\Webservices\RestApiDriverInterface;
use Creekfish\Models\Webservices\RestApiResponseType;

/**
 * Class GoogleMapsApi
 * @package Creekfish\Models\Webservices\Google\Maps
 *
 * Google Maps API service implementation.
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class GoogleMapsApi extends GoogleApi {

    /**
     * @var RestApiDriverInterface
     */
    private $driver;

    /**
     * @param RestApiDriverInterface $driver
     */
    public function __construct(RestApiDriverInterface $driver) {
        parent::__construct($driver);
    }

    /**
     * @return string
     */
    public function getApiRoute() {
        return '/maps/api';
    }

    /**
     * Geocode an address and return the coordinate from the response.
     *
     * @param string $address
     * @return Coordinate
     * @throws \RuntimeException If API response is malformed
     */
    public function geoCodeAddress($address) {
        // make the http request to the API
        $httpResponse = new HttpJsonResponseDecorator(
            $this->callApi('geocode', array('address' => $address), RestApiResponseType::JSON())
        );

        $data = $httpResponse->getJsonData();
        if (empty($data->results)
                || empty($data->results[0])
                || empty($data->results[0]->geometry)
                || empty($data->results[0]->geometry->location)) {
            throw new \RuntimeException('Malformed response from Google Maps API geocode address request. Content received: ' . $httpResponse->getContent());
        }

        // create and return Coordinate from the response
        //@todo Binding to creekfish\models here will probably become very awkward if webservices is moved to an external adapter project. Refactor to a common coordinate class?
        /** @var \Creekfish\Models\Coordinate $coord */
        $coordinate = \App::make('Creekfish\Models\Coordinate');
        $coordinate->fromArray(get_object_vars($data->results[0]->geometry->location));
        return $coordinate;
    }

    /**
     * Geocode an address provided in parts in the arguments
     * and return the coordinate from the response.
     *
     * @param string $street
     * @param string $city
     * @param string $state
     * @param string $zip
     * @param string $country
     * @return Coordinate
     */
    public function geoCodeAddressParts($street, $city, $state, $zip, $country = '') {

        $address = '';
        if (isset($street)) {
            $address .= "{$street} ";
        }
        if (isset($city)) {
            $address .= "{$city}, ";
        }
        if (isset($state)) {
            $address .= "{$state} ";
        }
        if (isset($zip)) {
            $address .= "{$zip} ";
        }
        if (isset($country)) {
            $address .= "{$country} ";
        }

        $address = trim($address);

        return $this->geoCodeAddress($address);
    }

    /**
     * Helper method to standardize Google Maps Api calls for this class.
     *
     * @param string $resourcePath
     * @param array $data
     * @param RestApiResponseType $responseType
     * @param HttpMethod $method
     * @return \Creekfish\Models\Webservices\Http\HttpResponse
     */
    protected function callApi($resourcePath, array $data = array(), RestApiResponseType $responseType = null, HttpMethod $method = null) {

        if ($resourcePath[0] !== '/') {
            $resourcePath = '/' . $resourcePath;  // add leading / to path if it's missing
        }

        $resourceRoute = $this->getApiRoute() . "{$resourcePath}";

        if (isset($responseType)) {
            $resourceRoute .= "/{$responseType->get()}";  // add response type if it's specified
        }

        if (!isset($method)) {
            $method = new HttpMethod(HttpMethod::GET);  // default to GET method
        }

        // use the driver to make the call and return the result
        return $this->getDriver()->restCall(
            $resourceRoute,
            $data,
            $method,
            $this->getApiHost()
        );
    }

}