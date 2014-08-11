<?php

namespace Creekfish\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class StatusType
 * @package Creekfish\Models
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class StatusType extends EloquentModelAbstract {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'status_types';

}