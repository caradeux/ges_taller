<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->after('folio');
        });

        // Change all decimal columns to integer (CLP has no decimals)
        $tables = [
            'work_orders' => ['deductible_amount', 'total_parts_cost', 'total_labor_cost', 'total_surcharge', 'tax_amount', 'total_amount', 'total_workshop', 'total_authorized', 'total_real_cost'],
            'work_order_items' => ['price_workshop', 'price_authorized', 'price_real'],
            'service_items' => ['default_price'],
            'part_orders' => ['cost'],
        ];

        foreach ($tables as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($columns) {
                    foreach ($columns as $col) {
                        if (Schema::hasColumn($table->getTable(), $col)) {
                            $table->integer($col)->default(0)->change();
                        }
                    }
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn('invoice_number');
        });
    }
};
