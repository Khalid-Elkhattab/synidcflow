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
        Schema::create('appartements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immeuble_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('resident_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('numero');
            $table->unsignedSmallInteger('etage')->nullable();
            $table->decimal('superficie', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appartements');
    }
};
