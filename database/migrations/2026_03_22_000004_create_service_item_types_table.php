<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_item_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Seed existing types
        DB::table('service_item_types')->insert([
            ['name' => 'Mano de Obra', 'slug' => 'mano_obra', 'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Repuesto',     'slug' => 'repuesto',   'active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Producto',     'slug' => 'producto',   'active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Change type column from enum to string
        DB::statement("ALTER TABLE service_items MODIFY COLUMN type VARCHAR(50) NOT NULL DEFAULT 'mano_obra'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE service_items MODIFY COLUMN type ENUM('repuesto','mano_obra','producto') NOT NULL DEFAULT 'mano_obra'");
        Schema::dropIfExists('service_item_types');
    }
};
