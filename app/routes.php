<?php

/**
 * @author Bill Herring <arcreekfish@gmail.com>
 */

// Restful API routes for resource CRUD operations

Route::group(
    array(
        'prefix' => 'api/v1',
        'before' => array(
//            'auth.basic.once'   // disable auth for now
        )
    ),
    function () {
        //@todo Make this a secure route, at least for POST and PUT actions where password is transmitted.
        Route::resource('/users', 'Creekfish\Controllers\UserController');
    }
);


// User entry form route (the default view)
// NOTE: the form in this view will end up in UserController::store when submitted (via a POST to /users)
Route::get('/', function () { return View::make('user-create-form'); });
