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
        Schema::create('riks', function (Blueprint $table) {
            $table->id('rikID');
            $table->string('nosaukums', 100);
            $table->text('apraksts')->nullable();
            $table->integer('daudzums')->default(0);
            $table->decimal('cenadiena', 10, 2);
            $table->string('foto', 255)->nullable();
            $table->string('kods', 50)->nullable();
            $table->string('zimols', 100)->nullable();
            $table->integer('nomasilgumsmin')->nullable();
            $table->integer('nomasilgumsmax')->nullable();
            $table->boolean('redzamsKatalogs')->default(true);
            $table->foreignId('kategorijaID')->constrained('kategorija', 'kategorijaID')->restrictOnDelete();
            $table->timestamps();

        });

            // CHECK ierobežojumi no prasībām
            if (DB::connection()->getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE riks ADD CONSTRAINT chk_riks_cena CHECK (cenadiena >= 0)');
                DB::statement('ALTER TABLE riks ADD CONSTRAINT chk_riks_daudzums CHECK (daudzums >= 0)');
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riks');
    }
};
