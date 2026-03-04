<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('un_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('name');
            $table->enum('category', ['repair', 'paint', 'dm', 'parts', 'other']);
            $table->unsignedTinyInteger('sort_order')->default(99);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        \DB::table('un_types')->insert([
            ['code' => 'REP',  'name' => 'Reparación',       'category' => 'repair', 'sort_order' => 1, 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PINT', 'name' => 'Pintura',          'category' => 'paint',  'sort_order' => 2, 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'D/M',  'name' => 'Desmontar/Montar', 'category' => 'dm',     'sort_order' => 3, 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'C',    'name' => 'Cambio',           'category' => 'parts',  'sort_order' => 4, 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'MAT',  'name' => 'Material',         'category' => 'other',  'sort_order' => 5, 'active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('un_types');
    }
};
