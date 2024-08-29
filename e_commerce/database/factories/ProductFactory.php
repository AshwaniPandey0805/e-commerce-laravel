<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $title = fake()->unique()->name();
        $slug = Str::slug($title);
        $brandArray = [11, 13];
        $brand_id = array_rand($brandArray);
        return [
            
            'title' => $title,
            'slug' => $slug,
            'category_id' => 103,
            'sub_category_id' => 17,
            'brand_id' => $brandArray[$brand_id],
            'price' => rand(100, 10000),
            'compare_price' => rand(100, 12000),
            'sku' => rand(10, 1000),
            'track_qty' => 'Yes',
            'qty' => rand(10, 50),
            'is_featured' => 'Yes',
            'status' => 1

        ];
    }
}
