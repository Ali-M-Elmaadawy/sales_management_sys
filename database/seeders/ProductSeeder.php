<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\File;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $products = [

            [
                'name' => 'Iphone 11',
                'price' => 11000,
                'qty' => 11,
                'sale_count' => 0,
                'tax_type' => 'percent',
                'tax' => 25,
                'description' => 'New Iphone 11',
                'image' => 'products/iphone11.png'
            ],
            [
                'name' => 'Iphone 12',
                'price' => 12000,
                'qty' => 12,
                'sale_count' => 0,
                'tax_type' => 'percent',
                'tax' => 25,
                'description' => 'New Iphone 12',
                'image' => 'products/iphone12.png'
            ],            
            [
                'name' => 'Iphone 13',
                'price' => 13000,
                'qty' => 13,
                'sale_count' => 0,
                'tax_type' => 'percent',
                'tax' => 25,
                'description' => 'New Iphone 13',
                'image' => 'products/iphone13.png'
            ],  
            [
                'name' => 'Iphone 14',
                'price' => 14000,
                'qty' => 14,
                'sale_count' => 0,
                'tax_type' => 'percent',
                'tax' => 20,
                'description' => 'New Iphone 14',
                'image' => 'products/iphone14.png'
            ],   
            [
                'name' => 'Iphone 15',
                'price' => 15000,
                'qty' => 15,
                'sale_count' => 0,
                'tax_type' => 'percent',
                'tax' => 20,
                'description' => 'New Iphone 15',
                'image' => 'products/iphone15.png'
            ],   
            [
                'name' => 'Iphone 16',
                'price' => 16000,
                'qty' => 16,
                'sale_count' => 0,
                'tax_type' => 'percent',
                'tax' => 20,
                'description' => 'New Iphone 16',
                'image' => 'products/iphone16.png'
            ],               
            [
                'name' => 'Iphone 17',
                'price' => 17000,
                'qty' => 17,
                'sale_count' => 0,
                'tax_type' => 'percent',
                'tax' => 17,
                'description' => 'New Iphone 17',
                'image' => 'products/iphone17.png'
            ]           
            
            
        ];
        $destinationDir = storage_path("app/public/products");

        if (!File::exists($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true);
        }

        for ($i = 11; $i <= 17; $i++) {

            $source = database_path("seed-images/products/iphone{$i}.png");

            $destination = $destinationDir . "/iphone{$i}.png";

            if (File::exists($source)) {
                File::copy($source, $destination);
            } else {
                dd("Source not found: " . $source);
            }
        }



        Product::insert($products);
    }
}
