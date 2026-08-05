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
        Schema::table('agent_conversations', function (Blueprint $table) {
            $table->foreignId('audit_id')
                ->nullable()
                ->unique()
                ->after('participant_id')
                ->constrained('audits')
                ->nullOnDelete();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_conversations', function (Blueprint $table) {
            $table->dropForeign(['audit_id']);
            $table->dropUnique(['audit_id']);
            $table->dropColumn('audit_id');
            $table->dropSoftDeletes();
        });
    }
};
