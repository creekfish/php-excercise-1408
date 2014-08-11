<?php

namespace Creekfish\Models\Enums;

use Creekfish\Lib\Enum;

/**
 * Class State
 * @package Creekfish\Models\Enums
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class State extends Enum {

	const AL = 'Alabama';
	const AK = 'Alaska';
	const AZ = 'Arizona';
	const AR = 'Arkansas';
	const CA = 'California';
	const CO = 'Colorado';
	const CT = 'Connecticut';
	const DE = 'Delaware';
	const DC = 'District Of Columbia';
	const FL = 'Florida';
	const GA = 'Georgia';
	const HI = 'Hawaii';
	const ID = 'Idaho';
	const IL = 'Illinois';
	const IN = 'Indiana';
	const IA = 'Iowa';
	const KS = 'Kansas';
	const KY = 'Kentucky';
	const LA = 'Louisiana';
	const ME = 'Maine';
	const MD = 'Maryland';
	const MA = 'Massachusetts';
	const MI = 'Michigan';
	const MN = 'Minnesota';
	const MS = 'Mississippi';
	const MO = 'Missouri';
	const MT = 'Montana';
	const NE = 'Nebraska';
	const NV = 'Nevada';
	const NH = 'New Hampshire';
	const NJ = 'New Jersey';
	const NM = 'New Mexico';
	const NY = 'New York';
	const NC = 'North Carolina';
	const ND = 'North Dakota';
	const OH = 'Ohio';
	const OK = 'Oklahoma';
	const _OR = 'Oregon';   // grrrr PHP, no "OR" for class const since it's reserved word
	const PA = 'Pennsylvania';
	const RI = 'Rhode Island';
	const SC = 'South Carolina';
	const SD = 'South Dakota';
	const TN = 'Tennessee';
	const TX = 'Texas';
	const UT = 'Utah';
	const VT = 'Vermont';
	const VA = 'Virginia';
	const WA = 'Washington';
	const WV = 'West Virginia';
	const WI = 'Wisconsin';
	const WY = 'Wyoming';

    /**
     * Override toArray to get rid of _ on _OR!
     * @return array<string, string>
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array = array_flip($array);
        array_walk($array,
            function (&$item, $key) {
                if ($item[0] === '_') {
                    $item = preg_replace('/^_+/', '', $item);
                }
            }
        );
        $array = array_flip($array);
        return $array;
    }

    /**
     * Return a JSON compatible value.
     * @return string
     */
    public function toJsonValue() {
        return $this->get();
    }
}