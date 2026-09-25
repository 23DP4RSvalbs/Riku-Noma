<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riks', function (Blueprint $table) {
            $table->string('statuss', 50)->default('pieejams')->after('daudzums');
        });

        DB::statement("ALTER TABLE riks ADD CONSTRAINT chk_riks_statuss CHECK (statuss IN ('pieejams', 'aizņemts', 'remonts', 'slēgts'))");
    }

    public function down(): void
    {
        Schema::table('riks', function (Blueprint $table) {
            $table->dropColumn('statuss');
        });
    }
};
