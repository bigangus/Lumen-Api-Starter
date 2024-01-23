<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Commands\CreateRole;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password');
            $table->timestamps();
        });

        $user = \App\Models\User::query()->create([
            'username' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('admin')
        ]);

        Artisan::call(CreateRole::class, [
            'name' => 'Basic'
        ]);

        $user->assignRole('Basic');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
