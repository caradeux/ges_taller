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
        Schema::dropIfExists('quotation_items');

        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['REP', 'D/M', 'C', 'MAT'])->default('REP');
            $table->string('description');
            $table->decimal('repair_price', 15, 2)->default(0);  // Reparación
            $table->decimal('paint_price',  15, 2)->default(0);  // Pintura
            $table->decimal('dm_price',     15, 2)->default(0);  // D/M
            $table->decimal('parts_price',  15, 2)->default(0);  // Valor Repuesto
            $table->decimal('other_price',  15, 2)->default(0);  // Otros
            $table->boolean('is_salvage')->default(false);
            $table->decimal('subtotal',     15, 2)->default(0);  // sum of all prices
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
