<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'producto' to service_items type enum
        DB::statement("ALTER TABLE service_items MODIFY COLUMN type ENUM('repuesto','mano_obra','producto') NOT NULL DEFAULT 'mano_obra'");

        // Add PROD UN type
        DB::table('un_types')->insert([
            'code'       => 'PROD',
            'name'       => 'Producto',
            'category'   => 'service',
            'sort_order' => 7,
            'active'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('un_types')->where('code', 'PROD')->delete();
        DB::statement("ALTER TABLE service_items MODIFY COLUMN type ENUM('repuesto','mano_obra') NOT NULL DEFAULT 'mano_obra'");
    }
};
