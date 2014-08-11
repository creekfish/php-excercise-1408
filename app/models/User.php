<?php

namespace Creekfish\Models;

use Creekfish\Models\Enums\StatusTypeName;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Http\Request;

/**
 * Class User
 * @package Creekfish\Models
 *
 * The user model, leveraging Laravel's Eloquent ORM models
 * with a lot of extras in parent classes!
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class User extends EloquentModelAbstract implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->email;
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

    public function status()
    {
        return $this->belongsTo('Creekfish\Models\StatusType', 'id');
    }

    /**
     * @return Coordinate
     */
    public function getLocation() {
        if (!isset($this->location) || $this->isDirty('location_json')) {
            $jsonArray = $this->getAttribute('location_json');
            if (!empty($jsonArray)) {
                $this->location = \App::make('Creekfish\Models\Coordinate');
                $this->location->fromJson($jsonArray);
            }
        }
        return $this->location;
    }

    /**
     * @param Coordinate $location
     * @return void
     */
    public function setLocation(Coordinate $location) {
        $this->setAttribute('location_json', $location->toJson());
    }

    /**
     * @return array
     */
    public function toArray() {
        // get array of all fields in the model table
        $ret = parent::toArray();

        // replace json location with Coordinate type
        $ret['location'] = $this->getLocation();
        unset($ret['location_json']);
        // set status type object in the array
        if (isset($this->status) && isset($this->status->name)) {
            $ret['status'] = new StatusTypeName($this->status->name);
        } else {
            $ret['status'] = null;
        }

        return $ret;
    }

    /**
     * @param Request $request
     * @param array $otherAttributes Other optional attributes to inject and use in building out the model
     * @return void
     */
    public function createFromRequest(Request $request = null, array $otherAttributes = array()) {
        if (!isset($request)) {
            $request = \Request::instance();
        }

        // required attributes

        $this->email = $request->get('email');
        $this->password = \Hash::make($request->get('password'));
        $this->first_name = $request->get('first_name');
        $this->last_name = $request->get('last_name');
        $this->city = $request->get('city');
        $this->state = $request->get('state');
        $this->zip = $request->get('zip');

        // optional attributes

        if ($request->has('biography')) {
            $this->biography = $request->get('biography');
        }

        $location = $this->getLocationJsonFromRequest($request);
        if (isset($location)) {
            $this->location_json = $location;
        }

        $statusName = StatusTypeName::ACTIVE();  // default to active status
        if ($request->has('status')) {
            $statusName = new StatusTypeName($request->get('status'));
        }
        $status = new StatusType();
        $status->name = $statusName->get();
        $this->status()->associate($status);

        $this->save();
    }

    /**
     * @param Request $request
     * @param array $otherAttributes Other optional attributes to inject and use in building out the model
     * @return void
     */
    public function updateFromRequest(Request $request = null, array $otherAttributes = array())
    {
        if (!isset($request)) {
            $request = \Request::instance();
        }

        if ($request->has('email')) {
            $this->email = $request->get('email');
        }
        if ($request->has('password')) {
            $this->password = \Hash::make($request->get('password'));
        }
        if ($request->has('first_name')) {
            $this->first_name = $request->get('first_name');
        }
        if ($request->has('last_name')) {
            $this->last_name = $request->get('last_name');
        }
        if ($request->has('city')) {
            $this->city = $request->get('city');
        }
        if ($request->has('state')) {
            $this->state = $request->get('state');
        }
        if ($request->has('zip')) {
            $this->zip = $request->get('zip');
        }
        if ($request->has('biography')) {
            $this->biography = $request->get('biography');
        }

        $location = $this->getLocationJsonFromRequest($request);
        if (isset($location)) {
            $this->location_json = $location;
        }

        if ($request->has('status')) {
            $statusName = new StatusTypeName($request->get('status'));
            $status = new StatusType();
            $status->name = $statusName->get();
            $this->status()->associate($status);
        }

        $this->push();
    }


    /**
     * Return json representation of location from the specified request
     * @param Request $request
     * @return string|null
     */
    private function getLocationJsonFromRequest(Request $request) {
        if ($request->has('location_json')) {
            // specify location via json
            return $request->get('location_json');
        } else if ($request->has('lat') && $request->has('lon')) {
            // specify location as lat, lon, elevation fields
            $jsonArray = array('lat' => $request->get('lat'), 'lon' => $request->get('lon'));
            if ($request->has('elevation')) {
                $jsonArray['elevation'] = $request->get('elevation');
            }
            return json_encode($jsonArray);
        }
        return null;
    }
}