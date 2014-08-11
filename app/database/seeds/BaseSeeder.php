<?php

/**
 * Class BaseSeeder
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
abstract class BaseSeeder extends Seeder {

    protected function getEntityId($entity, $col, $val) {
        $recs = DB::select('select id from '. $entity . ' where ' . $col . ' = ?', array($val));
        return array_pop($recs)->id;
    }

    protected function getTypeId($entity, $name) {
        $recs = DB::select('select id from '. $entity . '_types where name = ?', array($name));
        return array_pop($recs)->id;
    }

    protected function getUserId($email) {
        $recs = DB::select('select id from users where email = ?', array($email));
        return array_pop($recs)->id;
    }

    protected function getLorumIpsumText() {
        return 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
    }
}