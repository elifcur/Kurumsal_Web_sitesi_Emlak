<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Creates the initial super admin. Credentials come from the environment so
     * no password is ever committed to source control.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD', 'password');

        if (User::query()->where('email', $email)->exists()) {
            return;
        }

        User::query()->create([
            'name' => env('ADMIN_NAME', 'Yönetici'),
            'email' => $email,
            'password' => $password,
            'role' => UserRole::SuperAdmin,
            'is_active' => true,
        ]);

        $this->command?->warn("Süper yönetici oluşturuldu: {$email}. Üretimde ADMIN_PASSWORD değerini mutlaka değiştirin.");
    }
}
