<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a test user account to the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if test user already exists
        $existingUser = User::where('email', 'demo@agri.local')->first();
        
        if ($existingUser) {
            $this->info('Test user already exists!');
            $this->line('Email: demo@agri.local');
            $this->line('Password: password123');
            return 0;
        }

        // Create new test user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@agri.local',
            'password' => Hash::make('password123'),
        ]);

        $this->info('Test user created successfully!');
        $this->line('Email: demo@agri.local');
        $this->line('Password: password123');
        $this->line('User ID: ' . $user->id);

        // List all users
        $this->line("\nAll users in database:");
        $users = User::all();
        foreach ($users as $user) {
            $this->line("- {$user->email} (ID: {$user->id})");
        }

        return 0;
    }
}
