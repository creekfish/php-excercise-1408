<?php

namespace Creekfish\Models\Webservices\Http;

use Creekfish\Lib\HttpStatusCodes;

/**
 * Class HttpJsonResponseDecorator
 * @package Creekfish\Models\Webservices\Http
 *
 * Decorate an HttpResponseInterface where result content is JSON data
 * with methods to parse and handle the content.
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class HttpJsonResponseDecorator implements HttpResponseInterface {

    /**
     * @var HttpResponseInterface
     */
    private $basicResponse;

    /**
     * @var array
     */
    private $jsonData;

    public function __construct(HttpResponseInterface $basicResponse)
    {
        $this->basicResponse = $basicResponse;
    }

    /**
     * Return array of JSON data parsed from the content of the response.
     * @return array
     */
    public function getJsonData() {
        if (!isset($this->jsonData)) {
            $this->jsonData = json_decode($this->getContent());
        }
        return $this->jsonData;
    }

    /*
     * Delegate all basic interface methods.
     */

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->basicResponse->getContent();
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->basicResponse->getHttpCode();
    }

    /**
     * Is the body of the response empty?
     * @return bool
     */
    public function isEmpty() {
        return $this->basicResponse->isEmpty();
    }

    /**
     * Is the response an error based on the HTTP response code?
     * @return bool
     */
    public function isError() {
        return $this->basicResponse->isError();
    }

}