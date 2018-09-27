<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBoardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('boards', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id');
			$table->string('name', 266)->nullable()->default('');
			$table->text('description', 65535)->nullable();
			$table->text('columns', 65535);
			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->integer('public')->default(0);
			$table->string('public_hash', 266)->default('');
			$table->integer('lock')->default(0);
			$table->integer('default')->default(0);
			$table->integer('parent_board_id')->nullable();
			$table->integer('department_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('boards');
	}

}
