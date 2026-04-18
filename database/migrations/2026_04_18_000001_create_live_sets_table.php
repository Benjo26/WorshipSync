<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('live_set_song', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_set_id')->constrained()->cascadeOnDelete();
            $table->foreignId('song_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position');
            $table->timestamps();

            $table->unique(['live_set_id', 'song_id']);
            $table->unique(['live_set_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_set_song');
        Schema::dropIfExists('live_sets');
    }
};
