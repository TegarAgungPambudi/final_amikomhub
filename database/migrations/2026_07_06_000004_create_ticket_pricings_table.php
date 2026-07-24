<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->enum('tier', ['early_bird', 'presale_1', 'presale_2', 'regular'])->default('regular');
            $table->string('tier_name')->nullable(); // Human readable: "Early Bird", "Presale 1", etc.
            $table->bigInteger('price');
            $table->integer('stock')->default(0); // 0 = unlimited
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure one event can have only one active pricing per tier
            $table->unique(['event_id', 'tier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_pricings');
    }
};

