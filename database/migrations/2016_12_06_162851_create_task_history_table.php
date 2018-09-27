<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_history', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('task_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->enum('change_action', array('OWNER','PRIORITY','TYPE','DESCRIPTION',' SUBJECT','BOARD','PROJECT','ESTIMATE'))->nullable();
			$table->string('new_value', 50)->nullable();
			$table->dateTime('change_date')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_history');
	}

}
