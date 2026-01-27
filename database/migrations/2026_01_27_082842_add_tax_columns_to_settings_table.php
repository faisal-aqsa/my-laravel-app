<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('sgst', 5, 2)->nullable()->default(0.00)->after('website_url');
            $table->decimal('cgst', 5, 2)->nullable()->default(0.00)->after('sgst');
            $table->decimal('igst', 5, 2)->nullable()->default(0.00)->after('cgst');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['sgst', 'cgst', 'igst']);
        });
    }
};