<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'categories_name' => 'Bonsai Outdoor',
                'image' => 'storage/categories/outdoor-bonsai.jpeg',
                'description' => 'Bonsai yang cocok ditanam di luar ruangan, membutuhkan sinar matahari langsung'
            ],
            [
                'categories_name' => 'Bonsai Indoor',
                'image' => 'storage/categories/indoor-bonsai.jpeg',
                'description' => 'Bonsai yang bisa hidup dengan baik di dalam ruangan dengan pencahayaan sedang'
            ],
            [
                'categories_name' => 'Bonsai Buah',
                'image' => 'storage/categories/fruit-bonsai.jpeg',
                'description' => 'Bonsai yang menghasilkan buah kecil-kecil yang bisa dimakan'
            ],
            [
                'categories_name' => 'Bonsai Berbunga',
                'image' => 'storage/categories/flowering-bonsai.jpeg',
                'description' => 'Bonsai dengan bunga-bunga indah yang bermekaran'
            ],
            [
                'categories_name' => 'Bonsai Klasik',
                'image' => 'storage/categories/classic-bonsai.jpeg',
                'description' => 'Bonsai dengan gaya tradisional yang sudah ada sejak lama'
            ]
        ];

        foreach ($categories as $category) {
            Categories::create($category);
        }

        $products = [
            // Bonsai Outdoor
            [
                'categories_id' => 1,
                'name' => 'Bonsai Pinus',
                'image' => 'storage/products/pinus-bonsai.jpeg',
                'description' => 'Bonsai pinus dengan batang kokoh dan daun jarum yang hijau segar',
                'price' => 1250000,
                'discount' => 0,
                'stock' => 15
            ],
            [
                'categories_id' => 1,
                'name' => 'Bonsai Maple',
                'image' => 'storage/products/maple-bonsai.jpeg',
                'description' => 'Bonsai maple dengan daun yang berubah warna sesuai musim',
                'price' => 1850000,
                'discount' => 10, // 10% discount
                'stock' => 8
            ],

            // Bonsai Indoor
            [
                'categories_id' => 2,
                'name' => 'Bonsai Ficus',
                'image' => 'storage/products/ficus-bonsai.jpeg',
                'description' => 'Bonsai ficus yang sangat cocok untuk pemula dan tahan dalam ruangan',
                'price' => 750000,
                'discount' => 0,
                'stock' => 20
            ],
            [
                'categories_id' => 2,
                'name' => 'Bonsai Serut',
                'image' => 'storage/products/serut-bonsai.jpeg',
                'description' => 'Bonsai serut dengan daun kecil-kecil dan pertumbuhan yang cepat',
                'price' => 950000,
                'discount' => 15, // 15% discount
                'stock' => 12
            ],

            // Bonsai Buah
            [
                'categories_id' => 3,
                'name' => 'Bonsai Jeruk Nagami',
                'image' => 'storage/products/jeruk-bonsai.jpeg',
                'description' => 'Bonsai jeruk nagami yang menghasilkan buah jeruk kecil yang bisa dimakan',
                'price' => 2200000,
                'discount' => 0,
                'stock' => 5
            ],
            [
                'categories_id' => 3,
                'name' => 'Bonsai Delima',
                'image' => 'storage/products/delima-bonsai.jpeg',
                'description' => 'Bonsai delima dengan bunga merah dan buah delima mini',
                'price' => 1950000,
                'discount' => 5, // 5% discount
                'stock' => 7
            ],

            // Bonsai Berbunga
            [
                'categories_id' => 4,
                'name' => 'Bonsai Azalea',
                'image' => 'storage/products/azalea-bonsai.jpeg',
                'description' => 'Bonsai azalea dengan bunga berwarna-warni yang cantik',
                'price' => 1650000,
                'discount' => 0,
                'stock' => 10
            ],
            [
                'categories_id' => 4,
                'name' => 'Bonsai Soka',
                'image' => 'storage/products/soka-bonsai.jpeg',
                'description' => 'Bonsai soka dengan bunga merah kecil yang bermekaran sepanjang tahun',
                'price' => 1350000,
                'discount' => 20, // 20% discount
                'stock' => 6
            ],

            // Bonsai Klasik
            [
                'categories_id' => 5,
                'name' => 'Bonsai Juniper',
                'image' => 'storage/products/juniper-bonsai.jpeg',
                'description' => 'Bonsai juniper klasik dengan gaya informal upright yang elegan',
                'price' => 2500000,
                'discount' => 0,
                'stock' => 4
            ],
            [
                'categories_id' => 5,
                'name' => 'Bonsai Beringin',
                'image' => 'storage/products/beringin-bonsai.jpeg',
                'description' => 'Bonsai beringin dengan akar udara yang dramatis dan daun mengkilap',
                'price' => 2800000,
                'discount' => 10, // 10% discount
                'stock' => 3
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
