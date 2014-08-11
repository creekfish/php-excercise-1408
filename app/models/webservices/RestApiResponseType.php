<?php

namespace Creekfish\Models\Webservices;

use Creekfish\Lib\Enum;

/**
 * Class RestApiResponseType
 * @package Creekfish\Models\Webservices
 *
 * Enumeration of common response types for Rest API requests
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 *
 * @method static JSON
 * @method static XML
 * @method static HTML
 * @method static TEXT
 */
class RestApiResponseType extends Enum {

    const JSON = 'json';
    const XML = 'xml';
    const HTML = 'html';
    const TEXT = 'text';

    /**
     * Return content type string for this response type.
     *
     * @return string
     */
    public function getContentType() {

        switch ($this->get()) {
            case self::JSON:
                return 'application/json';
                break;
            case self::XML:
                return 'text/xml';
                break;
            case self::HTML:
                return 'text/html';
                break;
            case self::TEXT:
                return 'text/plain';
                break;
        }

        return null;
    }
}