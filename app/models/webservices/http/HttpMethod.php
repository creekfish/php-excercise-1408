<?php

namespace Creekfish\Models\Webservices\Http;

use Creekfish\Lib\Enum;

/**
 * Class HttpMethod
 * @package Creekfish\Models\Webservices\Http
 *
 * HTTP method enumeration - type checking is handy for preventing bugs!
 *
 * @author Bill Herring <arcrekfish@gmail.com>
 */
class HttpMethod extends Enum {

    const GET = 'get';
    const POST = 'post';
    const PUT = 'put';
    const DELETE = 'delete';
    const PATCH = 'patch;';
    const HEAD = 'head';
    const OPTIONS = 'options';
    const TRACE = 'trace';
    const CONNECT = 'connect';

}