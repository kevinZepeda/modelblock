<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionnarieTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('questionnarie_templates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id');
			$table->integer('reference_id')->nullable();
			$table->string('name', 200)->nullable();
			$table->text('template')->nullable();
			$table->integer('public')->default(0);
			$table->enum('type', array('TEMPLATE','QA'))->default('TEMPLATE');
			$table->enum('target', array('CUSTOMER','PROJECT'))->nullable();
			$table->enum('status', array('PENDING','SUBMITTED','REVIEWED'))->nullable();
			$table->timestamp('submission_date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('reviewer_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('questionnarie_templates');
	}

}
