<?php

namespace Creekfish\Controllers;

use Illuminate\Http\Response;
use Creekfish\Lib\HttpStatusCodes;
use Creekfish\Models\EloquentApiModel;
use Creekfish\Models\User;

/**
 * Restful user resource controller including a biography and location info.
 *
 * NOTE: this controller implements a Laravel "resource controller" interface
 * and follows that pattern.
 *
 * Class UserController
 * @package Creekfish\Controllers
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class UserController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $models = User::with('status')->get();
        return $this->getCollectionResponse($models);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

    /**
     * @return Response
     */
    public function store()
    {
        try {
            $validator = $this->createValidator();
            if ($validator->passes()) {

                /** @var \Creekfish\Models\Webservices\Google\Maps\GoogleMapsApi $mapsApi */
                $mapsApi = \App::make('Creekfish\Models\Webservices\Google\Maps\GoogleMapsApi');
                $coordinate = $mapsApi->geoCodeAddressParts('', \Request::get('city'), \Request::get('state'), \Request::get('zip'));
                $request = \Request::instance();
                $request->merge($coordinate->toArray());

                return parent::store();
            } else {
                return $this->getJsonErrorMessagesResponse(
                    $validator->getMessageBag()->all(),
                    HttpStatusCodes::HTTP_BAD_REQUEST
                );
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {  // unique constraint violation
                // assume email address...
                return $this->getJsonErrorMessagesResponse(array('Email address is already registered.'));
            }
            //@todo Log error details privately with key and show key instead.
            //@todo Have Laravel default error handler return JsonErrorMessagesResponse
            return $this->getJsonErrorMessagesResponse(array($e->getCode() . ': ' . $e->getMessage()));
        }
    }

    /**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $model = User::with('status')->find($id);
        return $this->getResourceResponse($model);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        /** @var EloquentApiModel $model */
        $model = User::with('status')->find($id);
        if (!isset($model)) {
            return $this->getJsonErrorResponse(array(), HttpStatusCodes::HTTP_NOT_FOUND);
        }

        $model->updateFromRequest(\Request::instance());

        return $this->getUpdateResponse($model);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $model = User::with('status')->find($id);

        $model->delete();

        return $this->getDeleteResponse();
    }

    /**
     * Create and return Laravel validator for user form POST data.
     *
     * @todo Inject this instead and compose in with the IoC container.
     *
     * @return \Illuminate\Validation\Validator
     */
    private function createValidator()
    {
        // add custom validation rules
        \Validator::extend('contains_caps',
            function($field, $value, $params)
            {
                return preg_match('#[A-Z]#', $value);
            }
        );

        \Validator::extend('contains_digit',
            function($field, $value, $params)
            {
                return preg_match('#[0-9]#', $value);
            }
        );

        // set array of messages for custom rules
        $messages = array(
            'contains_caps' => ':attribute must contain at least one capital letter.',
            'contains_digit' => ':attribute must contain at least one numeric digit.'
        );

        // set array of rules to use for validation (many are Laravel's built-ins)
        $rules = array(
            'email' => 'required|email|max:255',
            'password' => 'required|between:8,50|contains_caps|contains_digit',  // password has some "strength" requirements
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'city' => 'required|max:100',
            'state' => 'required|max:2',
            'zip' => 'required|max:20',
            'biography' => 'required',
        );

        $validator = \Validator::make(\Input::all(), $rules, $messages);

        return $validator;
    }
}