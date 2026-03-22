<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE un_types MODIFY COLUMN category ENUM('repair','paint','dm','parts','other','service') NOT NULL");

        DB::table('un_types')->insert([
            'code'       => 'SERV',
            'name'       => 'Servicio',
            'category'   => 'service',
            'sort_order' => 6,
            'active'     => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('un_types')->where('code', 'SERV')->delete();
        DB::statement("ALTER TABLE un_types MODIFY COLUMN category ENUM('repair','paint','dm','parts','other') NOT NULL");
    }
};
