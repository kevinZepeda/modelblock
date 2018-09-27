<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersExtendedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_extended', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->nullable()->unique('user_id');
			$table->integer('account_id')->nullable();
			$table->integer('active')->default(1);
			$table->enum('user_level', array('ADMIN','USER','MANAGER','CLIENT'))->default('USER');
			$table->string('first_name', 50)->nullable();
			$table->string('last_name', 50)->nullable();
			$table->string('language', 3)->default('ENG');
			$table->string('avatar', 266)->nullable();
			$table->dateTime('stopwatch_start')->nullable();
			$table->integer('customer_id')->unsigned()->nullable()->index('CustomerRelation');
			$table->integer('department_id')->unsigned()->nullable()->index('DepartmentRelation');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users_extended');
	}

}
