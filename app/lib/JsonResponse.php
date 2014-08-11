<?php

namespace Creekfish\Lib;

/**
 * Standardized JSON response, extending basic Laravel response with
 * a simple envelope (not quite JSON API standard).
 *
 * Class JsonResponse
 * @package Creekfish\Lib
 *
 * @author Bill Herring <arcrekfish@gmail.com>
 */
class JsonResponse extends \Illuminate\Http\JsonResponse {

    /**
     * @param mixed $data
     * @param int $status
     * @param boolean $isError
     * @param array $headers
     */
    public function __construct($data = null, $status = HttpStatusCodes::HTTP_OK, $isError = false, $headers = array()) {
        parent::__construct($this->getJsonData($isError, $data), $status, $headers);
    }

    public function getJsonData($isError, $data) {
        $count = 1;
        if (is_array($data)) {
            $count = count($data);
        }
        return array(
            'count' => $count,
            'data' => $data,
            'error' => $isError,
        );
    }

}