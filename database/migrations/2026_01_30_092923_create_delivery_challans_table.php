<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_challans', function (Blueprint $table) {
            $table->id();
            $table->string('challan_number')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->text('consignee_address')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->date('challan_date');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_challans');
    }
};