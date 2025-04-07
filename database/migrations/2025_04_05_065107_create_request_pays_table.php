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
        Schema::create('request_pays', function (Blueprint $table) {
            $table->id();
            $table->string("external_id");
            $table->string("user_id");
            $table->string("status");
            $table->string("merchant_name");
            $table->string("merchant_profile_picture_url");
            $table->integer("amount");
            $table->string("payer_email");
            $table->string("description");
            $table->string("expiry_date");
            $table->string("invoice_url");
            $table->unsignedBigInteger("reservation_id");
            $table->foreign("reservation_id")->references("id")->on("reservations");
            $table->timestamps("");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_pays');
    }
};
