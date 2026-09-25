<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pasutijuma_riks', function (Blueprint $table) {
            $table->id('pasutijumarikID');
            $table->integer('daudzums_pozicija')->default(1);
            $table->date('nomassakums');
            $table->date('nomasbeigums');
            $table->foreignId('rikID')->constrained('riks', 'rikID')->restrictOnDelete();
            $table->foreignId('pasutijumsID')->constrained('pasutijums', 'pasutijumsID')->cascadeOnDelete();
            $table->timestamps();

            // Indeksi pieejamības pārbaudei
            $table->index(['nomassakums', 'nomasbeigums'], 'idx_pasutijuma_riks_datumi');

            
        });

         if (DB::connection()->getDriverName() === 'mysql') {
             DB::statement('ALTER TABLE pasutijuma_riks ADD CONSTRAINT chk_pr_daudzums CHECK (daudzums_pozicija >= 0)');
             DB::statement('ALTER TABLE pasutijuma_riks ADD CONSTRAINT chk_pr_datumi CHECK (nomasbeigums >= nomassakums)');
         }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasutijuma_riks');
    }
};
