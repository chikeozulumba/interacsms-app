<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('user_id');
            $table->string('sender');
            $table->text('content', 100000);
            $table->integer('pages');
            $table->integer('volume');
            $table->text('recipients', 100000);
            $table->float('cost');
            $table->string('format')->nullable();
            $table->string('flash')->nullable();
            $table->string('brodcast_time')->default("None");
            $table->string('permission')->nullable();
            $table->integer('sent')->default(0);
            $table->integer('isDeleted')->default(0);
            $table->integer('deliveryReport')->default(0);
            $table->timestamps();
        });

        Schema::table('messages', function (Blueprint $table) {
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
        Schema::dropIfExists('messages');
    }
}
