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
       Schema::create('entreprise_training_seats', function (Blueprint $table) {
        $table->id();
        $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
        $table->foreignId('training_id')->constrained()->onDelete('cascade');
        $table->integer('seats_purchased')->default(0);
        $table->integer('seats_used')->default(0);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprise_training_seats');
    }
};
