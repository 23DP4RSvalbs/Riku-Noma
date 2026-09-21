<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasutijums', function (Blueprint $table) {
            $table->id('pasutijumsID');
            $table->decimal('kopsumma', 10, 2)->default(0);
            $table->string('statuss', 50)->default('gaida');
            $table->timestamp('izveidesdatums')->useCurrent();

            $table->unsignedBigInteger('lietotajID');
            $table->timestamps();

            
            $table->foreign('lietotajID')
                  ->references('lietotajsID')
                  ->on('lietotajs')
                  ->restrictOnDelete();

            $table->index('lietotajID');
            $table->index('statuss');
        });

        DB::statement('ALTER TABLE pasutijums ADD CONSTRAINT chk_pasutijums_kopsumma CHECK (kopsumma >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('pasutijums');
    }
};