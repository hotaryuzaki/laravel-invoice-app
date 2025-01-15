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
        DB::table('customers')->insert([
            [
                'name' => 'John Doe',
                'address' => '456 Elm Street, Springfield',
                'email' => 'johndoe@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'address' => '789 Maple Avenue, Shelbyville',
                'email' => 'janesmith@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Acme Corp.',
                'address' => '101 Industrial Road, Metropolis',
                'email' => 'info@acmecorp.com',
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
        DB::table('customers')->whereIn('email', [
            'johndoe@example.com',
            'janesmith@example.com',
            'info@acmecorp.com',
        ])->delete();
    }
};
