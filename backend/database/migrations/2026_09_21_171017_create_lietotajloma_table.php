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
        Schema::create('lietotajloma', function (Blueprint $table) {
           $table->unsignedBigInteger('lietotajID');
            $table->unsignedBigInteger('lomasID');

            $table->timestamps();

            $table->foreign('lietotajID')
                  ->references('LietotajsID')
                  ->on('lietotajs')
                  ->cascadeOnDelete();

            $table->foreign('lomasID')
                  ->references('LomasID')
                  ->on('loma')
                  ->restrictOnDelete();

            $table->unique(['lietotajID', 'lomasID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lietotajloma');
    }
};
