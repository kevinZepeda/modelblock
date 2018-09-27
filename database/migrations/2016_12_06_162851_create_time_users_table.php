<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimeUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('time_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id');
			$table->integer('user_id');
			$table->string('value', 100)->default('');
			$table->text('comment', 65535)->nullable();
			$table->time('time');
			$table->dateTime('date');
			$table->integer('billable')->default(0);
			$table->integer('approved')->default(0);
			$table->integer('comment_request')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('time_users');
	}

}
