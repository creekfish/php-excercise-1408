<?php

namespace Creekfish\Controllers;

use Illuminate\Http\Response;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Collection;
use \Illuminate\Http\Request;
use Creekfish\Lib\HttpStatusCodes;
use Creekfish\Lib\JsonResponse;

/**
 * Base class for all resource controllers, includes helper methods
 * for returning standardized responses.
 *
 * Class BaseController
 * @package Creekfish\Controllers
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class BaseController extends \Controller {

    private $resourceName;


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $model = \App::make($this->getResourceName() . 'FromRequest');
        return $this->getCreateResponse($model, $model->id);
    }

    /**
     * Set attributes on the given model from the given
     * request fields.
     *
     * @param Model $model
     * @param Request $request
     */
    public function setAttributesFromRequest(Model $model, Request $request = null)
    {
        if (!isset($request)) {
            $request = \Request::instance();
        }

        foreach ($model->getAttributes() as $attr) {
            if ($request->has($attr)) {
                $model->setAttribute($attr, $request->get($attr));
            }
        }

	}

    /**
     * Return the name of the resource this Controller serves
     *
     * @return string
     */
    protected function getResourceName() {
        if (!isset($this->resourceName)) {
            $this->resourceName = $this->getControllerResourceName();
        }
        return $this->resourceName;
    }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = \View::make($this->layout);
		}
	}

    /**
     * Return true if the given resource id is a legal wildcard.
     *
     * @param int|string $id
     * @return bool
     */
    protected function isWildcardId($id)
    {
        return ($id === \Config::get('app.wildcardResourceId'));
    }

    /**
     * Return a JSON resource response based on the provided model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return JsonResponse
     */
    protected function getResourceResponse(Model $model) {
        if (empty($model)) {
            return $this->getJsonErrorResponse(
                array('message' => HttpStatusCodes::getMessageForCode(HttpStatusCodes::HTTP_NOT_FOUND)),
                HttpStatusCodes::HTTP_NOT_FOUND
            );
        }

        return $this->getJsonResponse(array($this->toJsonableArray($model->toArray())));
    }

    /**
     * Return a JSON resource collection response based on the provided model collection.
     *
     * @param \Illuminate\Database\Eloquent\Collection $models
     * @param int $matchId Optional, set if the collection is supposed to contain one, specific resource
     * @return JsonResponse
     */
    protected function getCollectionResponse(Collection $models, $matchId = null) {
        if (count($models) === 0) {
            return $this->getJsonErrorResponse(
                array('message' => HttpStatusCodes::getMessageForCode(HttpStatusCodes::HTTP_NOT_FOUND)),
                HttpStatusCodes::HTTP_NOT_FOUND
            );
        }

        if (isset($matchId) && $models[0]->id != $matchId) {
            return $this->getJsonErrorResponse(
                array('message' => HttpStatusCodes::getMessageForCode(HttpStatusCodes::HTTP_BAD_REQUEST) . ": invalid resource path for {$this->getResourceName()} {$matchId}."),
                HttpStatusCodes::HTTP_BAD_REQUEST
            );
        }

        return $this->getJsonResponse($this->toJsonableArray($models->toArray()));
    }

    /**
     * Return a JSON resource creation (POST) response based on the provided new model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param int $newId
     * @return JsonResponse
     */
    protected function getCreateResponse(Model $model, $newId) {
        return $this->getJsonResponse(
            array($model->toArray()),
            HttpStatusCodes::HTTP_CREATED,
            false,
            array('Location' => \URL::current() . '/' . $newId)
        );
    }

    /**
     * Return a JSON resource update (PUT) response based on the provided updated model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return JsonResponse
     */
    protected function getUpdateResponse(Model $model) {
        return $this->getJsonResponse(array($model->toArray()));
    }

    /**
     * Return a JSON resource delete (DELETE) response.
     *
     * @return JsonResponse
     */
    protected function getDeleteResponse() {
        return \Response::make('', HttpStatusCodes::HTTP_NO_CONTENT);
    }

    /**
     * @param mixed $data
     * @param int $status
     * @param boolean $error
     * @param array $headers
     * @return JsonResponse
     */
    protected function getJsonResponse($data, $status = HttpStatusCodes::HTTP_OK, $error = false, $headers = array()) {
        return new JsonResponse($data, $status, $error, $headers);
    }

    /**
     * Return a standard JSON error response.
     *
     * @param mixed $data
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    protected function getJsonErrorResponse($data, $status, $headers = array()) {
        return new JsonResponse($data, $status, true, $headers);
    }

    /**
     * Return a standard JSON error response with "messages" list payload
     *
     * @param array $messages
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    protected function getJsonErrorMessagesResponse(array $messages, $status = HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR, $headers = array()) {
        $data = array('messages' => $messages);
        return $this->getJsonErrorResponse($data, $status);
    }

    /**
     * Since objects only json_encode() their public members,
     * and since public members are not generally desirable,
     * allow classes to implement toJsonValue to change the value
     * to expose the desired members before json encode processes
     * the data.
     *
     * @param array $array
     * @return array
     */
    private function toJsonableArray(array $array) {
        foreach ($array as $key => &$val) {
            if (is_object($val)) {
                if (method_exists($val, 'toJsonValue')) {
                    $val = $val->toJsonValue();
                }
            }
            if (is_array($val)) {
                // recurse if there are sub-arrays to transform
                $val = $this->toJsonableArray($val);
            }
        }
        return $array;
    }

    /**
     * Return the name for this controller (class name minus the "Controller" part)
     *
     * @return string
     */
    private function getControllerResourceName() {
        $className = $this->getClassName();
        $controllerStart = strpos($className, 'Controller');
        if ($controllerStart === false) {
            return $className; // return the class name by default
        }
        return substr($className, 0, $controllerStart);
    }

    /**
     * Parse out and return the class name from fully qualified name
     *
     * @param string $class
     * @return string
     */
    private function getClassName($class = null) {
        $parts = $this->parseClass($class);
        return $parts['classname'];
    }

    /**
     * Quick and dirty class namespace parser.
     *
     * NOTE: I am not terribly happy returning hash here, but in a hurry and it's a private helper...
     *
     * @param string $class
     * @return array
     */
    private function parseClass($class = null) {
        if (!isset($class)) {
            $class = get_class($this);
        }
        return array(
            'namespace' => array_slice(explode('\\', $class), 0, -1),
            'classname' => join('', array_slice(explode('\\', $class), -1)),
        );
    }
}