<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_order_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('event_type', 50);
            $table->string('description')->nullable();
            $table->dateTime('occurred_at');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('work_order_id');
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_events');
    }
};
