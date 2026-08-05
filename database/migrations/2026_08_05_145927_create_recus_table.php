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
        Schema::create('recus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('charge_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('fichier');
            $table->string('nom_original');
            $table->string('type_mime');
            $table->unsignedBigInteger('taille');
            $table->date('date_paiement');
            $table->decimal('montant_paye', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recus');
    }
};
