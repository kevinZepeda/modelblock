<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id');
			$table->string('customer_name', 100)->nullable();
			$table->string('company_number', 100)->nullable();
			$table->string('country', 3)->nullable();
			$table->string('city', 100)->nullable();
			$table->string('address', 200)->nullable();
			$table->string('postal_code', 100)->nullable();
			$table->string('contact_full_name', 200)->nullable();
			$table->string('phone_number', 50)->nullable();
			$table->string('email', 150)->nullable();
			$table->string('b_customer_name', 100)->nullable();
			$table->string('b_vat', 100)->nullable();
			$table->string('b_country', 3)->nullable();
			$table->string('b_city', 100)->nullable();
			$table->string('b_address', 200)->nullable();
			$table->string('b_postal_code', 100)->nullable();
			$table->string('b_contact_full_name', 200)->nullable();
			$table->string('b_phone_number', 50)->nullable();
			$table->string('b_email', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customers');
	}

}
