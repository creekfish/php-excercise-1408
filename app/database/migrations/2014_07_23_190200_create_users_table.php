<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUsersTable
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
            $table->increments('id');
            $table->unsignedInteger('status_type_id');  // active, inactive, deleted, etc.
            $table->string('email', 255)->unique();
            $table->string('password', 50);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('city', 100);
            $table->string('state', 2);
            $table->string('zip', 20);
            $table->string('location_json', 255);  // lat/lon/elev
            $table->text('biography');
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}