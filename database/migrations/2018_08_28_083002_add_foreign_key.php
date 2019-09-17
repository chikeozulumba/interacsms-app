<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('contacts', function (Blueprint $table) {
        //     $table->foreign('user_id')
        //             ->references('id')
        //             ->on('users')
        //             ->onDelete('cascade');
        // });
        // Schema::table('groups', function (Blueprint $table) {
        //     $table->foreign('user_id')
        //             ->references('id')
        //             ->on('users')
        //             ->onDelete('cascade');
        // });
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
