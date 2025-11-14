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
        Schema::create('trainings', function (Blueprint $table) {
    $table->id();
    $table->string('titre');
    $table->text('description')->nullable();
    $table->integer('duree');
    $table->enum('niveau', ['debutant', 'intermediaire', 'expert']);
    $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
    $table->foreignId('formateur_id')->constrained('users')->onDelete('cascade');
    $table->decimal('prix', 8, 2)->default(0);
    $table->date('date_debut');
    $table->integer('nombre_max_participants')->default(30);
    $table->enum('statut', ['en_cours', 'terminee', 'annulee'])->default('en_cours');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
