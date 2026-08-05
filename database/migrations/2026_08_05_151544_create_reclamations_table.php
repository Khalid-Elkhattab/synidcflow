<?php

use App\Enums\ReclamationPriorite;
use App\Enums\ReclamationStatut;
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
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('appartement_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('titre');
            $table->text('description');
            $table->string('statut')->default(ReclamationStatut::Submitted->value);
            $table->string('priorite')->default(ReclamationPriorite::Medium->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};
