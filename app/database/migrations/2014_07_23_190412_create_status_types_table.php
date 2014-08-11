<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateStatusTypesTable
 *
 * @author Bill Herring <arcreekfish@gmail.com>
 */
class CreateStatusTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('status_types', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('name', 50);
            $table->text('description');
            $table->unsignedInteger('rank');
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
		Schema::drop('status_types');
	}

}