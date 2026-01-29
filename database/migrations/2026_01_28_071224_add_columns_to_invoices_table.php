<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Add new columns
            $table->decimal('grand_total', 10, 2)->nullable()->after('total_amount');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('grand_total');
            $table->boolean('is_sgst')->default(false)->after('paid_amount');
            $table->boolean('is_cgst')->default(false)->after('is_sgst');
            $table->boolean('is_gst')->default(false)->after('is_cgst');
            $table->boolean('is_igst')->default(false)->after('is_gst');
            $table->text('consignee_address')->nullable()->after('is_igst');
            $table->string('e_way_bill_no', 50)->nullable()->after('consignee_address');
            $table->string('vehicle_no', 50)->nullable()->after('e_way_bill_no');
            $table->string('po_number', 100)->nullable()->after('vehicle_no');
            $table->date('paid_date')->nullable()->after('po_number');
            
            // Modify status enum to include 'partial_paid'
            $table->enum('status', ['pending', 'paid', 'overdue', 'partial_paid'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Drop added columns
            $table->dropColumn([
                'grand_total',
                'paid_amount',
                'is_sgst',
                'is_cgst',
                'is_gst',
                'is_igst',
                'consignee_address',
                'e_way_bill_no',
                'vehicle_no',
                'po_number',
                'paid_date'
            ]);
            
            // Revert status enum to original values
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending')->change();
        });
    }
};