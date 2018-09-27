<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id');
			$table->integer('project_id')->nullable();
			$table->integer('task_id')->nullable();
			$table->integer('user_id');
			$table->text('comment');
			$table->dateTime('comment_date');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_comments');
	}

}
