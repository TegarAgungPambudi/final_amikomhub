<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Check-in scanner fields
            $table->boolean('is_used')->default(false)->after('snap_token');
            $table->timestamp('used_at')->nullable()->after('is_used');
            $table->string('qr_code')->nullable()->after('used_at');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['is_used', 'used_at', 'qr_code']);
        });
    }
};

