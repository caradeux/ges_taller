<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company', function (Blueprint $table) {
            $table->unsignedInteger('ot_folio_counter')->default(1)->after('folio_counter');
        });

        // Initialize ot_folio_counter with current folio_counter value
        DB::statement('UPDATE company SET ot_folio_counter = folio_counter');
    }

    public function down(): void
    {
        Schema::table('company', function (Blueprint $table) {
            $table->dropColumn('ot_folio_counter');
        });
    }
};
