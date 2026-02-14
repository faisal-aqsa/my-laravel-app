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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('attention')->nullable();
            $table->string('quotation_for')->nullable();
            $table->date('date');
            $table->boolean('is_tax_included')->default(false);
            $table->boolean('is_delivery_charges_included')->default(false);
            $table->boolean('is_printing_included')->default(false);
            $table->boolean('is_plate_and_punch')->default(false);
            $table->boolean('is_lamination')->default(false);
            $table->softDeletes();
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
