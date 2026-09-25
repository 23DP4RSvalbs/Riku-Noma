<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riks', function (Blueprint $table): void {
            $table->string('statuss', 30)->default('pieejams')->after('redzamsKatalogs');
        });
    }

    public function down(): void
    {
        Schema::table('riks', function (Blueprint $table): void {
            $table->dropColumn('statuss');
        });
    }
};