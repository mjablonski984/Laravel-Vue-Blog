<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'id' => 1,
                'categoryName' => 'PHP',
                'iconImage' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/1920px-PHP-logo.svg.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'categoryName' => 'JavaScript',
                'iconImage' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/99/Unofficial_JavaScript_logo_2.svg/131px-Unofficial_JavaScript_logo_2.svg.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
