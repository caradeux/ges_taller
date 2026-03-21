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
            $table->json('stage_sla')->nullable()->after('ot_folio_counter');
        });

        // Set default SLA values (business days)
        DB::table('company')->update([
            'stage_sla' => json_encode([
                'intake'        => 2,
                'budget_sent'   => 5,
                'approved'      => 3,
                'waiting_parts' => 15,
                'in_repair'     => 10,
                'completed'     => 2,
                'delivered'     => 3,
            ]),
        ]);
    }

    public function down(): void
    {
        Schema::table('company', function (Blueprint $table) {
            $table->dropColumn('stage_sla');
        });
    }
};
