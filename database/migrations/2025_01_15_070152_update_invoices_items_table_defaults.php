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
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->integer('quantity')->default(0)->change();
            $table->decimal('unit_price', 15, 2)->default(0)->change();
            $table->decimal('amount', 15, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->integer('quantity')->default(null)->change();
            $table->decimal('unit_price', 15, 2)->default(null)->change();
            $table->decimal('amount', 15, 2)->default(null)->change();
        });
    }
};
