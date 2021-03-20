<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->string('telephone',12);
            $table->string('occupation',50);
            $table->string('physical_address',50);
            $table->string('town',20);
            $table->string('country',20);
            $table->string('postal_code',10);
            $table->string('s_first_name',50);
            $table->string('s_last_name',50);
            $table->string('s_town',20);
            $table->string('s_country',20);
            $table->string('s_postal_code',10);
            $table->string('s_telephone',12);
            $table->enum('role', ['USER', 'ADMIN']);
            $table->text('description');
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
