<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('messages', function (Blueprint $table) {
        //         $table->integer('deliveryReport')->default(0);
        //     });
        // Schema::table('logs', function (Blueprint $table) {
        //         $table->foreign('message_id')
        //                 ->references('id')
        //                 ->on('messages')
        //                 ->onDelete('cascade');
        //     });
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
