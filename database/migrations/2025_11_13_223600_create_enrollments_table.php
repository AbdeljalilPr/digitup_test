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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
             $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
             $table->enum('statut', ['en_attente', 'acceptee', 'terminee'])->default('en_attente');
             $table->decimal('note_finale', 5, 2)->nullable();
             $table->unique(['user_id', 'training_id']);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
