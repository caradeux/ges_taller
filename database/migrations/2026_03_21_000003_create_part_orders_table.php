<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('part_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_item_id')->constrained('work_order_items')->onDelete('cascade');
            $table->string('supplier')->nullable();
            $table->string('part_number')->nullable();
            $table->string('description');
            $table->decimal('cost', 15, 2)->default(0);
            $table->date('ordered_at')->nullable();
            $table->date('received_at')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index('work_order_item_id');
            $table->index('supplier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part_orders');
    }
};
