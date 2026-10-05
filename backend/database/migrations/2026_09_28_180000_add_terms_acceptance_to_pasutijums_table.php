<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasutijums', function (Blueprint $table) {
            $table->string('noteikumu_versija', 20)->nullable();
            $table->timestamp('noteikumi_apstiprinati_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pasutijums', function (Blueprint $table) {
            $table->dropColumn(['noteikumu_versija', 'noteikumi_apstiprinati_at']);
        });
    }
};