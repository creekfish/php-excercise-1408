<?php

namespace Creekfish\Models\Webservices\Http;

use Creekfish\Lib\HttpStatusCodes;

/**
 * Class HttpResponse
 * @package Creekfish\Models\Webservices\Http
 *
 * Basic instance of an HTTP response.
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class HttpResponse implements HttpResponseInterface {

    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $httpCode;


    function __construct($content, $httpCode = HttpStatusCodes::HTTP_OK)
    {
        $this->content = $content;
        $this->httpCode = $httpCode;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Is the body of the response empty?
     * @return bool
     */
    public function isEmpty() {
        return empty($this->content);
    }

    /**
     * Is the response an error based on the HTTP response code?
     * @return bool
     */
    public function isError() {
        return HttpStatusCodes::isError($this->getHttpCode());
    }

}