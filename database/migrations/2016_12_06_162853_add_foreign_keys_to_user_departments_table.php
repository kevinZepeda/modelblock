<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserDepartmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_departments', function(Blueprint $table)
		{
			$table->foreign('account_id', 'account_reference_department')->references('id')->on('account')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_departments', function(Blueprint $table)
		{
			$table->dropForeign('account_reference_department');
		});
	}

}
