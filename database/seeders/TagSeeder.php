<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
          [
            'id' => 1,
            'tagName' => 'Laravel',
            'created_at' => now(),
            'updated_at' => now(),
          ],  
          [
            'id' => 2,
            'tagName' => 'Vue.js',
            'created_at' => now(),
            'updated_at' => now(),
          ],  
          [
            'id' => 3,
            'tagName' => 'API',
            'created_at' => now(),
            'updated_at' => now(),
          ],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
