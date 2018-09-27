<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSharepointTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sharepoint', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id');
			$table->integer('project_id');
			$table->string('file_hash', 244)->default('');
			$table->string('name', 244)->default('');
			$table->text('tags', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sharepoint');
	}

}
