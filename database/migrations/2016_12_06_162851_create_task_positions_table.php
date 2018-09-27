<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskPositionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_positions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('board_id')->nullable();
			$table->integer('task_id')->unsigned()->nullable()->index('task_reference');
			$table->integer('size_x')->nullable();
			$table->integer('size_y')->nullable();
			$table->integer('visible')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_positions');
	}

}
