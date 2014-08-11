<?php

namespace Creekfish\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class EloquentApiModel
 * @package Creekfish\Models
 *
 * Additional interface and functionality for
 * Api resource models.
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
abstract class EloquentApiModel extends EloquentModelAbstract {

    /**
     * Populate the model from request data, creating a new model.
     *
     * @param Request $request
     * @param array $otherAttributes
     * @return void
     */
    abstract public function createFromRequest(Request $request = null, array $otherAttributes = array());

    /**
     * Update the model from request data.
     *
     * @param Request $request
     * @param array $otherAttributes
     * @return void
     */
    abstract public function updateFromRequest(Request $request = null, array $otherAttributes = array());

}