<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop foreign keys that reference quotations
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropForeign(['quotation_id']);
        });

        // 2. Rename quotations → work_orders
        Schema::rename('quotations', 'work_orders');

        // 3. Add new columns to work_orders
        Schema::table('work_orders', function (Blueprint $table) {
            $table->decimal('total_workshop', 15, 2)->default(0)->after('total_amount');
            $table->decimal('total_authorized', 15, 2)->default(0)->after('total_workshop');
            $table->decimal('total_real_cost', 15, 2)->default(0)->after('total_authorized');
        });

        // 4. Migrate status values and total_workshop
        DB::statement("UPDATE work_orders SET total_workshop = total_amount");
        DB::statement("UPDATE work_orders SET status = 'intake' WHERE status = 'draft'");
        DB::statement("UPDATE work_orders SET status = 'budget_sent' WHERE status = 'sent'");
        DB::statement("UPDATE work_orders SET status = 'completed' WHERE status = 'finished'");
        DB::statement("UPDATE work_orders SET status = 'intake' WHERE status = 'rejected'");

        // 5. Change status column to new enum
        DB::statement("ALTER TABLE work_orders MODIFY COLUMN status ENUM('intake','budget_sent','approved','waiting_parts','in_repair','completed','delivered','invoiced') NOT NULL DEFAULT 'intake'");

        // 6. Rename quotation_items → work_order_items
        Schema::rename('quotation_items', 'work_order_items');

        // 7. Modify work_order_items
        Schema::table('work_order_items', function (Blueprint $table) {
            $table->renameColumn('quotation_id', 'work_order_id');
            $table->renameColumn('price', 'price_workshop');
        });

        Schema::table('work_order_items', function (Blueprint $table) {
            $table->decimal('price_authorized', 15, 2)->default(0)->after('price_workshop');
            $table->decimal('price_real', 15, 2)->default(0)->after('price_authorized');
            $table->boolean('is_approved')->default(true)->after('price_real');
        });

        // 8. Re-add foreign key
        Schema::table('work_order_items', function (Blueprint $table) {
            $table->foreign('work_order_id')->references('id')->on('work_orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Drop FK
        Schema::table('work_order_items', function (Blueprint $table) {
            $table->dropForeign(['work_order_id']);
        });

        // Remove new columns from items
        Schema::table('work_order_items', function (Blueprint $table) {
            $table->dropColumn(['price_authorized', 'price_real', 'is_approved']);
        });

        // Rename columns back
        Schema::table('work_order_items', function (Blueprint $table) {
            $table->renameColumn('work_order_id', 'quotation_id');
            $table->renameColumn('price_workshop', 'price');
        });

        // Rename table back
        Schema::rename('work_order_items', 'quotation_items');

        // Revert status enum
        DB::statement("ALTER TABLE work_orders MODIFY COLUMN status ENUM('draft','sent','approved','rejected','finished','invoiced') NOT NULL DEFAULT 'draft'");
        DB::statement("UPDATE work_orders SET status = 'draft' WHERE status = 'intake'");
        DB::statement("UPDATE work_orders SET status = 'sent' WHERE status = 'budget_sent'");
        DB::statement("UPDATE work_orders SET status = 'finished' WHERE status = 'completed'");

        // Remove new columns
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['total_workshop', 'total_authorized', 'total_real_cost']);
        });

        // Rename table back
        Schema::rename('work_orders', 'quotations');

        // Re-add FK
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
        });
    }
};
