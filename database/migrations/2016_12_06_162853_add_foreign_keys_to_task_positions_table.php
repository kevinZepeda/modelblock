<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTaskPositionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('task_positions', function(Blueprint $table)
		{
			$table->foreign('task_id', 'task_reference')->references('id')->on('tasks')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('task_positions', function(Blueprint $table)
		{
			$table->dropForeign('task_reference');
		});
	}

}
