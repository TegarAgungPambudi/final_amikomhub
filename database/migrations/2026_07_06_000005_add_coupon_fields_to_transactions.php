<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('coupon_code')->nullable()->after('status');
            $table->bigInteger('discount_amount')->default(0)->after('coupon_code');
            $table->bigInteger('original_price')->nullable()->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'discount_amount', 'original_price']);
        });
    }
};

