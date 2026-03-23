<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Helper to drop all FKs from a table
        $dropAllForeignKeys = function (string $tableName) {
            $fks = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'", [$tableName]);
            if (! empty($fks)) {
                Schema::table($tableName, function (Blueprint $table) use ($fks) {
                    foreach ($fks as $fk) {
                        $table->dropForeign($fk->CONSTRAINT_NAME);
                    }
                });
            }
        };

        // 1. Drop foreign keys from quotation_items (if table still exists)
        if (Schema::hasTable('quotation_items')) {
            $dropAllForeignKeys('quotation_items');
        }

        // 2. Rename quotations → work_orders
        if (Schema::hasTable('quotations') && ! Schema::hasTable('work_orders')) {
            Schema::rename('quotations', 'work_orders');
        }

        // 3. Add new columns to work_orders (if not already added)
        Schema::table('work_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('work_orders', 'total_workshop')) {
                $table->decimal('total_workshop', 15, 2)->default(0)->after('total_amount');
            }
            if (! Schema::hasColumn('work_orders', 'total_authorized')) {
                $table->decimal('total_authorized', 15, 2)->default(0)->after('total_workshop');
            }
            if (! Schema::hasColumn('work_orders', 'total_real_cost')) {
                $table->decimal('total_real_cost', 15, 2)->default(0)->after('total_authorized');
            }
        });

        // 4. Change status column to new enum FIRST (must happen before UPDATEs)
        DB::statement("ALTER TABLE work_orders MODIFY COLUMN status ENUM('draft','sent','approved','rejected','finished','invoiced','intake','budget_sent','waiting_parts','in_repair','completed','delivered') NOT NULL DEFAULT 'intake'");

        // 5. Migrate status values and total_workshop
        DB::statement("UPDATE work_orders SET total_workshop = total_amount WHERE total_workshop = 0");
        DB::statement("UPDATE work_orders SET status = 'intake' WHERE status = 'draft'");
        DB::statement("UPDATE work_orders SET status = 'budget_sent' WHERE status = 'sent'");
        DB::statement("UPDATE work_orders SET status = 'completed' WHERE status = 'finished'");
        DB::statement("UPDATE work_orders SET status = 'intake' WHERE status = 'rejected'");

        // 6. Narrow enum to only new values
        DB::statement("ALTER TABLE work_orders MODIFY COLUMN status ENUM('intake','budget_sent','approved','waiting_parts','in_repair','completed','delivered','invoiced') NOT NULL DEFAULT 'intake'");

        // 6. Rename quotation_items → work_order_items
        if (Schema::hasTable('quotation_items') && ! Schema::hasTable('work_order_items')) {
            Schema::rename('quotation_items', 'work_order_items');
        }

        // 7. Modify work_order_items columns
        if (Schema::hasColumn('work_order_items', 'quotation_id')) {
            Schema::table('work_order_items', function (Blueprint $table) {
                $table->renameColumn('quotation_id', 'work_order_id');
            });
        }
        if (Schema::hasColumn('work_order_items', 'price') && ! Schema::hasColumn('work_order_items', 'price_workshop')) {
            Schema::table('work_order_items', function (Blueprint $table) {
                $table->renameColumn('price', 'price_workshop');
            });
        }

        Schema::table('work_order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('work_order_items', 'price_authorized')) {
                $table->decimal('price_authorized', 15, 2)->default(0)->after('price_workshop');
            }
            if (! Schema::hasColumn('work_order_items', 'price_real')) {
                $table->decimal('price_real', 15, 2)->default(0)->after('price_authorized');
            }
            if (! Schema::hasColumn('work_order_items', 'is_approved')) {
                $table->boolean('is_approved')->default(true)->after('price_real');
            }
        });

        // 8. Re-add foreign key (if not already present)
        $existingFks = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'work_order_items' AND CONSTRAINT_TYPE = 'FOREIGN KEY'");
        if (empty($existingFks)) {
            Schema::table('work_order_items', function (Blueprint $table) {
                $table->foreign('work_order_id')->references('id')->on('work_orders')->onDelete('cascade');
            });
        }
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
