<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pay_gates', function (Blueprint $table) {
            $table->id();
            $table->string('external_id');
            $table->string('user_id');
            $table->string('is_high');
            $table->string('payment_method');
            $table->string('status');
            $table->string('merchant_name');
            $table->integer('amount');
            $table->integer('paid_amount');
            $table->string('bank_code');
            $table->string('paid_at');
            $table->string('payer_email');
            $table->text('description');
            $table->integer('adjusted_received_amount');
            $table->integer('fees_paid_amount');
            $table->string('currency');
            $table->string('payment_channel');
            $table->string('payment_destination');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_gates');
    }
};

