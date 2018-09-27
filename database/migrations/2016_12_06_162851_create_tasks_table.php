<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id')->nullable();
			$table->integer('manager_id')->unsigned()->nullable()->index('manage_relation');
			$table->integer('project_id')->unsigned()->nullable()->index('project_relation');
			$table->integer('type')->nullable();
			$table->text('subject', 65535)->nullable();
			$table->text('description')->nullable();
			$table->string('estimate', 266)->nullable();
			$table->integer('priority')->default(900);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tasks');
	}

}
