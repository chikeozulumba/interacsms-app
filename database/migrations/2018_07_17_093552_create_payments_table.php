<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('user_id');
            $table->text('trx_ref', 10000);
            $table->text('reference', 10000);
            $table->float('amount', 255);
            $table->integer('payout')->default(0);
            $table->bigInteger('units')->default(0);
            $table->string('P_message')->nullable();
            $table->string('P_status')->nullable();
            $table->string('P_reference')->nullable();
            $table->string('P_paid_at')->nullable();
            $table->string('P_created_at')->nullable();
            $table->ipAddress('P_ipAddress')->nullable();
            $table->string('P_authorization_code')->nullable();
            $table->string('P_bin')->nullable();
            $table->string('P_last4')->nullable();
            $table->string('P_exp_month')->nullable();
            $table->string('P_exp_year')->nullable();
            $table->string('P_channel')->nullable();
            $table->string('P_card_type')->nullable();
            $table->string('P_bank')->nullable();
            $table->string('P_country_code')->nullable();
            $table->string('P_brand')->nullable();
            $table->string('P_signature')->nullable();
            $table->boolean('P_reusable')->nullable();
            $table->string('P_customer_id')->nullable();
            $table->string('P_customer_code')->nullable();
            $table->string('P_createdAt')->nullable();
            $table->string('P_paidAt')->nullable();
            $table->string('P_transaction_date')->nullable();
            $table->timestamps();
        });

        Schema::table('payments', function (Blueprint $table) {
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
        Schema::dropIfExists('payments');
    }
}
