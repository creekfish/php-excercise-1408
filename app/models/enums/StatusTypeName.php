<?php

namespace Creekfish\Models\Enums;

use Creekfish\Lib\Enum;

/**
 * Class StatusTypeName
 * @package Creekfish\Models\Enums
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 *
 * @method static \Creekfish\Models\Enums\StatusTypeName INACTIVE
 * @method static \Creekfish\Models\Enums\StatusTypeName ACTIVE
 * @method static \Creekfish\Models\Enums\StatusTypeName DELETED
 */
class StatusTypeName extends Enum {
    const INACTIVE = 'inactive';
    const ACTIVE = 'active';
    const DELETED = 'deleted';


    /**
     * Return a JSON compatible value.
     * @return string
     */
    public function toJsonValue() {
        return $this->get();
    }
}