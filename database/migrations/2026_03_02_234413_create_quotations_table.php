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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->date('date');
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected', 'finished', 'invoiced'])->default('draft');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('insurance_company_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('liquidator_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('deductible_amount', 15, 2)->default(0);
            $table->decimal('total_parts_cost', 15, 2)->default(0);
            $table->decimal('total_labor_cost', 15, 2)->default(0);
            $table->decimal('total_surcharge', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
