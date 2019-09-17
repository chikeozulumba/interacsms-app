<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->integer('is_deliveryReport')->default(0);
            $table->string('mccMnc')->nullable();
            $table->string('title')->nullable();
            $table->text('message', 10000)->nullable();
            $table->string('sentAt')->nullable();
            $table->string('doneAt')->nullable();
            $table->integer('smsCount')->nullable();
            $table->float('price_per_message')->nullable();
            $table->string('price_currency')->nullable();
            $table->integer('delivery_status_groupId')->nullable();
            $table->string('delivery_status_groupName')->nullable();
            $table->string('delivery_status_id')->nullable();
            $table->string('delivery_status_name')->nullable();
            $table->text('delivery_status_description')->nullable();
            $table->integer('delivery_error_groupId')->nullable();
            $table->string('delivery_error_groupName')->nullable();
            $table->integer('delivery_error_id')->nullable();
            $table->string('delivery_error_name')->nullable();
            $table->text('delivery_error_description')->nullable();
            $table->boolean('delivery_error_permanent')->nullable();
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
