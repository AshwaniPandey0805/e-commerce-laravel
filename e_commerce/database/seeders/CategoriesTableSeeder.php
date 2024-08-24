<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $categories = [];

        for ($i = 0; $i < 20; $i++) {
            $categories[] = [
                'name' => $faker->word,
                'slug' => $faker->slug,
                'image' => $faker->imageUrl(640, 480, 'cats', true, 'Faker'), // Generates a placeholder image URL
                'status' => $faker->boolean ? 1 : 0, // Randomly set status to 1 or 0
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('categories')->insert($categories);
    }
}
