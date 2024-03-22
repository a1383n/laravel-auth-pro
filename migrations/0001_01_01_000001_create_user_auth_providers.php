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
        Schema::create('authenticatable_providers', function (Blueprint $table) {
            $table->id();
            $table->morphs('authenticatable');
            $table->string('provider_type', 16);
            $table->string('provider_id', 16);
            $table->json('payload')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authenticatable_providers');
    }
};
