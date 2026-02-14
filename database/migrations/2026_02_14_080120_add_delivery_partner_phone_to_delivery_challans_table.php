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
        Schema::table('delivery_challans', function (Blueprint $table) {
            $table->string('delivery_partner_phone', 20)
                ->nullable()
                ->after('vehicle_no')
                ->comment('Delivery partner contact number for tracking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_challans', function (Blueprint $table) {
            $table->dropColumn('delivery_partner_phone');
        });
    }
};
