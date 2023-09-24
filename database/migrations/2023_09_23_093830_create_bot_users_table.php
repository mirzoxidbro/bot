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
        Schema::create('bot_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatId')->index()->unique();
            $table->string('step')->nullable();
            $table->string('fullname')->nullable();
            $table->boolean('gender')->nullable();
            $table->integer('age')->nullable();
            $table->string('specialty')->nullable();
            $table->string('experience')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_users');
    }
};
