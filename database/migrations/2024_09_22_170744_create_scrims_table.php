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
        Schema::create('scrims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_from_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('team_to_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('date');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrims');
    }
};
