<?php

namespace Creekfish\Models\Webservices\Http;

/**
 * Class HttpResponseInterface
 * @package Creekfish\Models\Webservices\Http
 *
 * Basic HTTP response interface
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
interface HttpResponseInterface {

    /**
     * @return string
     */
    public function getContent();

    /**
     * @return int
     */
    public function getHttpCode();

    /**
     * Is the body of the response empty?
     * @return bool
     */
    public function isEmpty();

    /**
     * Is the response an error based on the HTTP response code?
     * @return bool
     */
    public function isError();

}