<?php

namespace Creekfish\Models\Webservices\Exceptions;

use Creekfish\Lib\HttpStatusCodes;
use Creekfish\Models\Webservices\Http\HttpResponse;

/**
 * Class HttpErrorResponseException
 * @package Creekfish\Models\Webservices\Exceptions
 *
 * Exception that can be thrown if http request returns an error response.
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class HttpErrorResponseException extends \RuntimeException{

    /**
     * @var \Creekfish\Models\Webservices\Http\HttpResponse
     */
    private $response;

    /**
     * @param string $message
     * @param HttpResponse $response
     * @param \Exception $previousException
     */
    public function __construct($message, HttpResponse $response, \Exception $previousException = null) {
        parent::__construct($message, $response->getHttpCode(), $previousException);
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getHttpCode() {
        return $this->getResponse()->getHttpCode();
    }

    /**
     * @return HttpResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

}