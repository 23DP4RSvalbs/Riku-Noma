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
        Schema::create('lietotajs', function (Blueprint $table) {
            $table->id('lietotajsID');
            $table->string('vards', 100);
            $table->string('epasts', 100)->unique();
            $table->string('parole');
            $table->string('telefons', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lietotajs');
    }
};
