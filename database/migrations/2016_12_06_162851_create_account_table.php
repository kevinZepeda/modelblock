<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('account', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('active')->default(1);
			$table->string('system_label', 50)->default('<b>Agile</b>Team');
			$table->string('company_name', 266)->nullable();
			$table->string('timezone', 100)->nullable()->default('UTC');
			$table->string('currency', 3)->nullable()->default('EUR');
			$table->string('address', 266)->nullable();
			$table->string('country', 3)->nullable();
			$table->string('postal_code', 50)->nullable();
			$table->string('city', 50)->nullable();
			$table->string('vat', 50)->nullable();
			$table->string('phone_number', 20)->nullable();
			$table->string('email', 100)->nullable();
			$table->string('invoice_logo', 266)->nullable();
			$table->string('invoice_prefix', 100)->nullable();
			$table->integer('invoice_padding')->nullable();
			$table->text('invoice_note')->nullable();
			$table->text('invoice_legal_note')->nullable();
			$table->string('invoice_language', 3)->default('ENG');
			$table->string('invoice_layout_color', 20)->nullable();
			$table->string('system_layout_color', 20)->default('#909090');
			$table->string('system_layout_text_color', 20)->nullable()->default('#ffffff');
			$table->string('system_logo', 266)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('account');
	}

}
