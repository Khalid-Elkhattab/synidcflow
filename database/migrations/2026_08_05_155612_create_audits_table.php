<?php

use App\Enums\AuditStatut;
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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reclamation_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->json('charges_snapshot');
            $table->json('resultat')->nullable();
            $table->string('decision')->nullable();
            $table->string('statut')->default(AuditStatut::Pending->value);
            $table->string('modele_ia')->nullable();
            $table->timestamp('traite_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
