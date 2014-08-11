<?php

use Illuminate\Database\Seeder;

/**
 * Class StatusTypesTableSeeder
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class StatusTypesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('status_types')->delete();

        $types = array(
            array(
                'name' => 'inactive',
                'description' => '',
                'rank' => 100
            ),
            array(
                'name' => 'active',
                'description' => '',
                'rank' => 200
            ),
            array(
                'name' => 'deleted',
                'description' => '',
                'rank' => 300
            ),
        );

        DB::table('status_types')->insert($types);
    }
}