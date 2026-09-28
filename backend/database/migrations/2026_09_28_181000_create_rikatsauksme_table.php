<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rikatsauksme', function (Blueprint $table) {
            $table->id('rikatsauksmeID');
            $table->foreignId('rikID')->constrained('riks', 'rikID')->cascadeOnDelete();
            $table->foreignId('lietotajID')->constrained('lietotajs', 'lietotajsID')->cascadeOnDelete();
            $table->unsignedTinyInteger('vertejums');
            $table->string('teksts', 1000);
            $table->timestamps();
            $table->unique(['rikID', 'lietotajID']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rikatsauksme');
    }
};