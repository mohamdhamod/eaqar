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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('restrict');
            $table->integer('used_properties')->default(0);
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            // Unique constraint: user can have only one active subscription at a time per subscription type
            $table->unique(['user_id', 'subscription_id']);
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('subscription_id');
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
