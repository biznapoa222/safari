<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAccessSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['Shishi ERP Administrator', 'erp@biznapoa.com', 'administrator', 'Administration', '+254 700 000 001', 'shishi2026'],
            ['Daniel Kimani', 'sales@shishifootsteps.test', 'sales', 'Sales', '+254 700 000 002', 'password'],
            ['Linet Achieng', 'reservations@shishifootsteps.test', 'reservations', 'Reservations', '+254 700 000 003', 'password'],
            ['Joseph Maina', 'operations@shishifootsteps.test', 'operations', 'Operations', '+254 700 000 004', 'password'],
            ['Faith Wanjiku', 'finance@shishifootsteps.test', 'finance', 'Finance', '+254 700 000 005', 'password'],
        ];

        foreach ($users as [$name, $email, $role, $department, $phone, $password]) {
            User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'role' => $role,
                    'department' => $department,
                    'phone' => $phone,
                    'is_active' => true,
                    'password' => Hash::make($password),
                ],
            );
        }

        User::query()->where('email', 'admin@safariflow.test')->delete();
    }
}
