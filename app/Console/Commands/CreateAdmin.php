<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->ask('Enter the name:');
        $email = $this->ask('Enter email:');
        $password = $this->secret('Enter password:');

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'type' => 'admin'
        ]);

        $this->line('Admin successfully create!');
    }
}
