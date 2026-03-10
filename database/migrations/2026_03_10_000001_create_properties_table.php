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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained('configurations')->onDelete('restrict');
            $table->foreignId('operation_type_id')->constrained('configurations')->onDelete('restrict');
            $table->foreignId('property_type_id')->constrained('configurations')->onDelete('restrict');
            $table->foreignId('currency_id')->constrained('configurations')->onDelete('restrict');
            $table->string('slug')->index();
            $table->decimal('price', 15, 2);
            $table->decimal('area', 10, 2)->nullable();
            $table->unsignedSmallInteger('rooms')->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->smallInteger('floor')->nullable();
            $table->unsignedSmallInteger('total_floors')->nullable();
            $table->unsignedSmallInteger('building_age')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['active', 'hidden', 'deleted'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Composite indexes for common filter queries
            $table->index(['status', 'active']);
            $table->index(['city_id', 'status', 'operation_type_id']);
            $table->index(['operation_type_id', 'property_type_id', 'status']);
            $table->index(['price']);
            $table->index(['is_featured', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
