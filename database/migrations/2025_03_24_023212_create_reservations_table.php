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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('table_id');
            $table->string('name');
            $table->integer('guests');
            $table->string('telephone');
            $table->string('email');
            $table->dateTime('date');
            $table->enum('status', ['Unpaid', 'Paid'])->default('Unpaid');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('table_id')->references('id')->on('tables');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};



