<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoiceItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id');
			$table->integer('invoice_id')->unsigned()->index('InvoiceId');
			$table->enum('item_type', array('ITEM','TAX','PRE-TAX'))->default('ITEM');
			$table->integer('quantity')->default(1);
			$table->text('label_1', 65535);
			$table->text('label_2', 65535);
			$table->string('factor', 100)->default('0');
			$table->decimal('item_cost', 10)->default(0.00);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoice_items');
	}

}
