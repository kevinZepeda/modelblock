<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('account_id')->nullable();
			$table->integer('customer_id')->unsigned()->nullable()->index('customer_relation');
			$table->integer('category_id')->unsigned()->nullable();
			$table->string('currency', 3)->default('EUR');
			$table->text('billed_from')->nullable();
			$table->text('billed_to')->nullable();
			$table->enum('type', array('INVOICE','QUOTE','DRAFT','RECURRING','EXPENSE'))->default('DRAFT');
			$table->string('invoice_number', 100)->nullable();
			$table->enum('status', array('UNPAID','PAID','STORNO','FRAUD','CREDIT','DEBT','COLLECT'))->default('UNPAID');
			$table->date('invoice_date')->nullable();
			$table->date('due_date')->nullable();
			$table->enum('r_due_period', array('MONTHLY','DAILY','WEEKLY','YEARLY'))->nullable();
			$table->integer('r_ready')->default(0);
			$table->integer('r_due_days')->nullable();
			$table->date('r_next_date')->nullable();
			$table->date('r_end_date')->nullable();
			$table->text('notes')->nullable();
			$table->text('legal_notes')->nullable();
			$table->string('language', 3)->default('ENG');
			$table->string('invoice_logo', 266)->nullable();
			$table->string('invoice_title', 266)->nullable();
			$table->float('invoice_subtotals', 10);
			$table->decimal('invoice_pre_tax', 10);
			$table->decimal('invoice_tax', 10);
			$table->integer('archived')->default(0);
			$table->string('layout_color', 20)->nullable()->default('');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoices');
	}

}
