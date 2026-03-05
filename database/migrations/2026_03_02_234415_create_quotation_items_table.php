<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['reparacion', 'dym', 'cambio', 'pintura', 'materiales']);
            $table->string('description');
            $table->decimal('parts_price', 15, 2)->default(0);
            $table->decimal('labor_price', 15, 2)->default(0);
            $table->decimal('others_price', 15, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('surcharge_percentage', 5, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
