<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_notifications', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('user_id');
            $table->string('message_id');
            $table->string('type_message')->nullable();
            $table->string('title');
            $table->text('message', 10000);
            $table->string('time_log');
            $table->integer('isRead')->default(0);
            $table->integer('isAllowed')->default(1);
            $table->timestamps();
        });

        Schema::table('message_notifications', function (Blueprint $table) {
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
        Schema::table('message_notifications', function (Blueprint $table) {
            $table->foreign('message_id')
                    ->references('id')
                    ->on('messages')
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
        Schema::dropIfExists('notifications');
    }
}
