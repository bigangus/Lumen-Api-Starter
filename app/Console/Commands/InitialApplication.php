<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class InitialApplication extends Command
{
    protected $signature = 'app:initial';

    protected $description = 'First command when you launch the app';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Artisan::call('migrate:fresh');
        $this->info('Database tables created');
        Artisan::call(GeneratePermissions::class);
        $this->info('Permissions and roles created');

        $adminName = $this->ask('Please input admin name');
        $adminPassword = $this->ask('Please input admin password');

        $user = User::query()->create([
            'username' => $adminName,
            'password' => Hash::make($adminPassword)
        ]);

        if ($user) {
            $this->info('Admin user '.$user->username.' created');
        }

        $user->assignRole('Super Admin');

        $this->info('Next Step: ');
        $this->info('Make sure you set supervisor for php artisan queue:work');
    }
}
