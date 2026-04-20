<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [

            [
                'name' => 'ali-elmaadawy',
                'email' => 'alisonny2009@gmail.com',
                'phone' => '01002230585',
                'address' => 'cairo',
                'sale_count' => 0,
                'sale_products_count' => 0,
                'last_sale_date' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'mohamed-gomma',
                'email' => 'm@gmail.com',
                'phone' => '01002265412',
                'address' => 'ksa',
                'sale_count' => 0,
                'sale_products_count' => 0,
                'last_sale_date' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ibrahim-elsayed',
                'email' => 'i@gmail.com',
                'phone' => '01525478565',
                'address' => 'mansoura',
                'sale_count' => 0,
                'sale_products_count' => 0,
                'last_sale_date' => NULL,
                'created_at' => now(),
                'updated_at' => now()
            ]        
            
            
        ];

        User::insert($users);
    }
}
