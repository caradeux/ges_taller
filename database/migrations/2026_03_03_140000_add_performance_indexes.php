<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Quotations: las columnas más usadas en WHERE/ORDER BY
        Schema::table('quotations', function (Blueprint $table) {
            $table->index('status');
            $table->index('date');
            $table->index('branch_id');
            $table->index(['status', 'branch_id']);   // filtros combinados del dashboard
            $table->index(['date', 'status']);         // reportes por fecha y estado
        });

        // Clients
        Schema::table('clients', function (Blueprint $table) {
            $table->index('branch_id');
            $table->index('name');
        });

        // Vehicles
        Schema::table('vehicles', function (Blueprint $table) {
            $table->index('branch_id');
            $table->index('license_plate');
        });

        // Quotation items: join con quotation_id
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->index('quotation_id');
            $table->index('un_type_id');
        });

        // Users
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('branch_id');
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['date']);
            $table->dropIndex(['branch_id']);
            $table->dropIndex(['status', 'branch_id']);
            $table->dropIndex(['date', 'status']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['branch_id']);
            $table->dropIndex(['name']);
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex(['branch_id']);
            $table->dropIndex(['license_plate']);
        });

        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropIndex(['quotation_id']);
            $table->dropIndex(['un_type_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['branch_id']);
        });
    }
};
