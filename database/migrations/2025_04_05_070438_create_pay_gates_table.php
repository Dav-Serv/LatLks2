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
            $table->string('external_id')->nullable()->default(null);
            $table->string('user_id')->nullable()->default(null);
            $table->string('is_high')->nullable()->default(null);
            $table->string('payment_method')->nullable()->default(null);
            $table->string('status')->nullable()->default(null);
            $table->string('merchant_name')->nullable()->default(null);
            $table->integer('amount')->nullable()->default(0);
            $table->integer('paid_amount')->nullable()->default(0);
            $table->string('bank_code')->nullable()->default(null);
            $table->string('paid_at')->nullable()->default(null);
            $table->string('payer_email')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->integer('adjusted_received_amount')->nullable()->default(0);
            $table->integer('fees_paid_amount')->nullable()->default(0);
            $table->string('currency')->nullable()->default(null);
            $table->string('payment_channel')->nullable()->default(null);
            $table->string('payment_destination')->nullable()->default(null);
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

