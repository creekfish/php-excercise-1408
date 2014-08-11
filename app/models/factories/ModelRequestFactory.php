<?php

namespace Creekfish\Models\Factories;


use Illuminate\Http\Request;
use Creekfish\Models\EloquentApiModel;

/**
 * Class ModelRequestFactory
 * @package Creekfish\Models\Factories
 *
 * Simple factory for creation of models from request.
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class ModelRequestFactory {

    /**
     * Create a new model from data in the request,
     * assume request field names match model field names.
     *
     * @param Request|null|array $request
     * @param string $modelClass
     * @param array $otherAttributes
     * @return EloquentApiModel
     */
    public function createModelFromRequest($modelClass, $request, array $otherAttributes = array()) {
        if (empty($request)) {
            $request = null;
        }
        $model = $this->createModelFromBaseClassName($modelClass);
        $model->createFromRequest($request, $otherAttributes);
        return $model;
    }

    /**
     * @param string $baseClassName
     * @return EloquentApiModel
     */
    private function createModelFromBaseClassName($baseClassName) {
        if (substr($baseClassName, 0, 1) === '\\') {
            return new $baseClassName;  // assume fully qualified name
        }
        $className = '\Creekfish\Models\\' . $baseClassName;
        return new $className;
    }

}