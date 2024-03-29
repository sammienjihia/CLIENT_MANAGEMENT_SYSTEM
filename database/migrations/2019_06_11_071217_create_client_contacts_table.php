<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('alternative_email')->unique()->nullable();
            $table->string('alternative_phone_number')->unique()->nullable();
            $table->string('city')->nullable();
            $table->integer('zip_code')->nullable();
            $table->string('street')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address')->nullable();
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
        Schema::dropIfExists('client_contacts');
    }
}
