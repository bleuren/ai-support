<?php

declare(strict_types=1);

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
        Schema::create('team_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')
                ->constrained()
                ->casecadeOnDelete()
                ->casecadeOnUpdate();
            $table->string('key')->index();
            $table->string('description')->nullable();
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_settings');
    }
};
