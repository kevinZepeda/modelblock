<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinanceInvoices extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account', function(Blueprint $table)
        {
            $table->enum('invoice_number_format', array('DATEFORMAT','NUMBERFORMAT'))
                ->default('NUMBERFORMAT')
                ->after('invoice_layout_color');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account', function(Blueprint $table)
        {
            $table->dropColumn('invoice_number_format');
        });
    }
}
