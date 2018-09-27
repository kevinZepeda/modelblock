<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectRequirementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('project_requirements', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id');
			$table->integer('project_id');
			$table->text('subject');
			$table->text('details');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('project_requirements');
	}

}
