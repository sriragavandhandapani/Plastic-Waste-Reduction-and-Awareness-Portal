<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@econexus.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'Admin',
            'is_validated' => true,
            'status' => 'active',
        ]);

        // Proposer
        $proposer = User::create([
            'name' => 'Green Innovate',
            'email' => 'proposer@econexus.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'Proposer',
            'is_validated' => true,
            'status' => 'active',
        ]);

        // Campaign
        \App\Models\Campaign::create([
            'proposer_id' => $proposer->_id ?? $proposer->id,
            'title' => 'Biodegradable Bubble Wrap',
            'description' => 'A completely biodegradable alternative made from seaweed extract.',
            'category' => 'Packaging',
            'status' => 'active',
            'visibility' => 'public',
        ]);
        User::create([
        'name' => 'Ragav',
        'email' => 'sriragavandhandapani@email.com',
        'password' => bcrypt('admin1234'),
        'role' => 'Admin',
        'is_validated' => true,
        'status' => 'active',
        ]);

    }
}
