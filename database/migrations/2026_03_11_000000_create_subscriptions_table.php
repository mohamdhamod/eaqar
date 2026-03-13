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
        // Create subscriptions table
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2)->default(0);
            $table->foreignId('currency_id')->constrained('configurations')->onDelete('restrict');
            
            $table->integer('duration_days')->default(30);
            $table->integer('max_properties')->default(0);
            $table->json('features')->nullable();
            $table->string('icon')->nullable();
            $table->string('color', 7)->default('#667eea');
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('active');
            $table->index('sort_order');
            $table->index('currency_id');
           
        });

        // Create subscription_translations table
        Schema::create('subscription_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['subscription_id', 'locale']);
            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscriptions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_translations');
        Schema::dropIfExists('subscriptions');
    }
};
