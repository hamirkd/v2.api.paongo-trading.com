<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAchat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('achats', function (Blueprint $table) {
            // $table->string('moyenpaiement',50)->nullable()->change();
            // $table->string('adressepaiement',50)->nullable()->change();
            // $table->string('montantcrypto',50)->nullable()->change();
            // $table->string('montantcrypto_recu',50)->nullable()->change();
            // $table->string('transaction_code',50)->nullable()->change();
            // $table->string('transaction_hash',50)->nullable()->change();
            // $table->dateTime('date_paiement')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
