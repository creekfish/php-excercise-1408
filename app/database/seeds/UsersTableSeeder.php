<?php

use Creekfish\Models\User;

/**
 * Class UsersTableSeeder
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class UsersTableSeeder extends BaseSeeder {

    public function run()
    {
        DB::table('users')->delete();

        User::create(
            array(
                'first_name' => 'Bill',
                'last_name' => 'Herring',
                'password' => Hash::make('strongP@ssword'),
                'email' => 'fish@ozarkpages.com',
                'city' => 'Overland Park',
                'state' => 'KS',
                'zip' => '66223',
                'location_json' => '{"lat":12.2321,"lon":-9.231321,"elevation":1023}',
                'biography' => $this->getLorumIpsumText()
            )
        );

    }
}