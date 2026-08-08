<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('make:admin {name} {email} {password}', function ($name, $email, $password) {
    \App\Models\User::updateOrCreate(
        ['email' => $email],
        [
            'name' => $name,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'role' => 'admin', // Asumsi ada kolom role, atau sesuaikan logika role aplikasi
        ]
    );
    $this->info("Admin '{$name}' dengan email '{$email}' berhasil dibuat!");
})->purpose('Create an admin user');
