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
        DB::table('companies')->insert([
            [
                'name' => 'Tech Solutions Inc.',
                'address' => '123 Innovation Drive, Tech City',
                'email' => 'contact@techsolutions.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('companies')->where('email', 'contact@techsolutions.com')->delete();
    }
};
