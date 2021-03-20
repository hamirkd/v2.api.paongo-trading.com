<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAchatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nomprenom',50);
            $table->string('emailadresse',50);
            $table->string('telephone',50);
            $table->string('titre',50);
            $table->string('montant',50);
            $table->string('moyenpaiement',50)->nullable();
            $table->string('adressepaiement',50)->nullable();
            $table->string('montantcrypto',50)->nullable();
            $table->string('montantcrypto_recu',50)->nullable();
            $table->string('transaction_code',50)->nullable();
            $table->string('transaction_hash',50)->nullable();
            $table->enum('etat_paiement', ['NON_PAYE', 'PAYE']);
            $table->dateTime('date_paiement')->nullable();
            $table->enum('etat_investissement', ['NON_DEBUTE', 'DEBUTE']);
            $table->boolean('supprimer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('achats');
    }
}
