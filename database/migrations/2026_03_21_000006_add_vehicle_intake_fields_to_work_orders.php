<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // Intake/exit dates
            $table->date('exit_date')->nullable()->after('date');
            $table->date('repair_start_date')->nullable()->after('exit_date');

            // Vehicle intake inventory (checklist)
            $table->json('vehicle_inventory')->nullable()->after('notes');

            // Objects declaration
            $table->text('objects_declaration')->nullable()->after('vehicle_inventory');

            // Conductor name (who delivered the vehicle)
            $table->string('conductor_name')->nullable()->after('objects_declaration');
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn([
                'exit_date',
                'repair_start_date',
                'vehicle_inventory',
                'objects_declaration',
                'conductor_name',
            ]);
        });
    }
};
