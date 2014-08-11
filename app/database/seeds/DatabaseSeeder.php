<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

        // seed all "type" tables and other tables with no dependencies
        $this->call('StatusTypesTableSeeder');

        // seed tables in dependency order
        $this->call('UsersTableSeeder');
	}

}