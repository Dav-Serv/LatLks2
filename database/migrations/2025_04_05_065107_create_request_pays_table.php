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
            $table->unsignedBigInteger("reservation_id")->nullable(null);
            $table->unsignedBigInteger("order_id")->nullable(null);
            $table->foreign("reservation_id")->references("id")->on("reservations");
            $table->dropForeign(['order_id']); // Drop foreign key yang lama
            $table->foreign('order_id')
                  ->references('id')->on('orders')
                  ->onDelete('cascade'); // Menambahkan cascade delete
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


// ALTER TABLE request_pays
// 	ADD CONSTRAINT request_pays_order_id_foreign
//     FOREIGN KEY (order_id)
//     REFERENCES orders(id);