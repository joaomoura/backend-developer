<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CriarTabelaInstallments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('installment');
            $table->float('amount');
            $table->date('date');
            $table->BigInteger('sale_id');

            // $table->foreign('sale_id')
            //     ->references('sales')
            //     ->on('id');
        });

        Schema::table('installments', function($table) {
           $table->foreign('sale_id')->references('id')->on('sales');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('installments');
    }
}
