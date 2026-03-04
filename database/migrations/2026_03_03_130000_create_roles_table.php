<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();          // slug: admin, recepcion, taller
            $table->string('label', 100);                  // display: Administrador, Recepción, Taller
            $table->text('description')->nullable();
            $table->string('badge_color', 30)->default('#6b7280');
            $table->boolean('is_system')->default(true);   // system roles can't be deleted
            $table->timestamps();
        });

        // Seed the three system roles
        DB::table('roles')->insert([
            [
                'name'        => 'admin',
                'label'       => 'Administrador',
                'description' => 'Acceso completo al sistema. Puede gestionar usuarios, sucursales, catálogos y configuración.',
                'badge_color' => '#1e40af',
                'is_system'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'recepcion',
                'label'       => 'Recepción',
                'description' => 'Puede crear y gestionar cotizaciones, clientes y vehículos. Accede a reportes y seguimiento.',
                'badge_color' => '#0284c7',
                'is_system'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'taller',
                'label'       => 'Taller',
                'description' => 'Acceso de solo lectura a cotizaciones, clientes y vehículos. No puede crear ni editar.',
                'badge_color' => '#16a34a',
                'is_system'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
