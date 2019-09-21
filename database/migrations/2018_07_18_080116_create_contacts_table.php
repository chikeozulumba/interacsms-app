<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('user_id');
            $table->uuid('group_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_1');
            $table->string('phone_2')->nullable();
            $table->string('location');
            $table->integer('isDeleted')->default(0);
            $table->timestamps();
        });
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
