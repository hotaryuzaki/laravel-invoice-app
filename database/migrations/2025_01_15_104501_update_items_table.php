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
        DB::table('items')->insert([
            ['name' => 'Service Maintenance', 'type' => 'service', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Service Baterai', 'type' => 'service', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Service AC', 'type' => 'service', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Printer', 'type' => 'hardware', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laptop', 'type' => 'hardware', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Smartphone', 'type' => 'hardware', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'TV LED 42 inch', 'type' => 'hardware', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('items')->whereIn('name', [
            'Service Maintenance', 'Service Baterai', 'Service AC',
            'Printer', 'Laptop', 'Smartphone', 'TV LED 42 inch'
        ])->delete();
    }
};
