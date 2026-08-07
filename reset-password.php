<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Set email dan password baru di sini
$email = 'admin@test.com';
$newPassword = 'password';

$user = User::where('email', $email)->first();

if ($user) {
    $user->password = Hash::make($newPassword);
    $user->save();
    echo "Sukses! Password untuk {$email} direset menjadi: {$newPassword}\n";
} else {
    echo "Gagal. User dengan email {$email} tidak ditemukan.\n";
}
