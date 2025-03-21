<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminUser=User::create([
            'name' => 'SuperAdmin User', // Replace with your desired name
            'email' => 'superadmin@gmail.com', // Replace with your desired email
            'password' => Hash::make('12345678'), // Replace with a strong password
        ]);

        $superAdminUser->assignRole('SuperAdmin');

        $shopOwnerUser1=User::create([
            'name' => 'Kyaw Zin Lin', // Replace with your desired name
            'email' => 'kyawzinlinforgit@gmail.com', // Replace with your desired email
            'password' => Hash::make('12345678'), // Replace with a strong password
        ]);

        $shopOwnerUser1->assignRole('ShopOwner');

        $shopOwnerUser2=User::create([
            'name' => 'ShopOwner User 2', // Replace with your desired name
            'email' => 'shopowner2@gmail.com', // Replace with your desired email
            'password' => Hash::make('12345678'), // Replace with a strong password
        ]);

        $shopOwnerUser2->assignRole('ShopOwner');
    }
}
