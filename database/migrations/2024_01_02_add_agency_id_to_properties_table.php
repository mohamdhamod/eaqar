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
        Schema::table('properties', function (Blueprint $table) {
            // Add agency_id foreign key if properties table allows multiple agencies per user
            $table->foreignId('agency_id')
                ->nullable()
                ->after('user_id')
                ->constrained('agencies')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Agency::class);
            $table->dropColumn('agency_id');
        });
    }
};
