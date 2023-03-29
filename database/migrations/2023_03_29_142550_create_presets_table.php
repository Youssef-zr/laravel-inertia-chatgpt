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
        Schema::create('presets', function (Blueprint $table) {
            $table->id();
            $table->string('temperature')->nullable()->default(0);
            $table->string('frequency_penalty')->nullable()->default(0);
            $table->string('presence_penalty')->nullable()->default(0);
            $table->string('max_tokens')->nullable()->default(0);
            $table->string('top_p')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presets');
    }
};
