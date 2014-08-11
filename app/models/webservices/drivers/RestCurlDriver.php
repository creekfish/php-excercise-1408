<?php

namespace Creekfish\Models\Webservices\Drivers;

use Creekfish\Models\Webservices\Http\HttpMethod;
use Creekfish\Models\Webservices\Http\HttpResponse;
use Creekfish\Lib\HttpStatusCodes;
use Creekfish\Models\Webservices\RestApiDriverInterface;
use Creekfish\Models\Webservices\RestApiResponseType;

/**
 * Class RestCurlDriver
 * @package Creekfish\Models\Webservices\Drivers
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class RestCurlDriver implements RestApiDriverInterface {

    /**
     * @var string
     */
    private $host;

    /**
     * @param string $defaultHost
     */
    public function __construct($defaultHost = null) {
        $this->host = $defaultHost;
    }

    /**
     * @param string $host
     */
    public function setDefaultHost($host) {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getDefaultHost() {
        return $this->host;
    }

    /**
     * Submit a request to the given resource route using the data and method
     * provided.  Returns an HttpResponse.
     *
     * @todo Add ability to pass and set request headers for content negotiation, etc.
     *
     * @param string $resourceRoute URL route to the resource, relative to host root path
     * @param array $data Data to be passed with the request (e.g. in query string or POST data)
     * @param \Creekfish\Models\Webservices\Http\HttpMethod $method HTTP method for the request, GET is default method
     * @param string $host Override default host (optional)
     * @return HttpResponse
     * @throws \InvalidArgumentException
     */
    public function restCall($resourceRoute, array $data = array(), HttpMethod $method = null, $host = null) {
        if  (!isset($method)) {
            $method = new HttpMethod(HttpMethod::GET);
        }
        return $this->curl($this->getUrl($resourceRoute, $host), $data, $method);
    }

    /**
     * @param string $resourceRoute
     * @param string $host
     * @return string
     */
    private function getUrl($resourceRoute, $host = null) {
        if (!isset($host)) {
            $host = $this->getDefaultHost();
        }

        if ($resourceRoute[0] !== '/') {
            $resourceRoute = '/' . $resourceRoute;
        }

        return "{$host}{$resourceRoute}";
    }

    /**
     * Encapsulate PHP's curl extension calls in a protected method
     * so we can stub/mock as needed in unit tests.
     *
     * @param string $url
     * @param array $data
     * @param \Creekfish\Models\Webservices\Http\HttpMethod $method
     * @return HttpResponse
     * @throws \InvalidArgumentException
     */
    protected function curl($url, array $data, HttpMethod $method) {

        // convert data array into query string params
        $queryString = $this->buildQueryStringFromArray($data);

        $curl = curl_init();

        switch ($method->get()) {
            case HttpMethod::GET:
                $url = $url . '?' . $queryString;
                break;
            case HttpMethod::POST:
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $queryString);
                break;
            case HttpMethod::PUT:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $queryString);
                break;
            case HttpMethod::DELETE:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                throw new \InvalidArgumentException('HTTP method ' . $method->get() . ' not supported by ' . __CLASS__);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);

        $contents = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return new HttpResponse($contents, $httpCode);
    }

    /**
     * Build a query string from the provided data array.
     *
     * @todo this may eventually need to be a Strategy, but for now just encapsulate in a method
     *
     * @param array $data
     * @return string
     */
    protected function buildQueryStringFromArray(array $data) {
        return http_build_query($data);
    }

}