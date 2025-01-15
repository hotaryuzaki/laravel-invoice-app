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
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('sub_total', 15, 2)->default(0)->change();
            $table->decimal('tax', 15, 2)->default(0)->change();
            $table->decimal('grand_total', 15, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('sub_total', 15, 2)->default(null)->change();
            $table->decimal('tax', 15, 2)->default(null)->change();
            $table->decimal('grand_total', 15, 2)->default(null)->change();
        });
    }
};
