<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        $arr['brand_id'] = Brand::inRandomOrder()->first()->id;
        $arr['category_id'] = Category::inRandomOrder()->first()->id;
        $arr['name'] = fake()->name();
        $arr['slug'] = Str::slug($arr['name']);
        $arr['sku'] = fake()->ean8();
        $arr['image'] = fake()->imageUrl();
        $arr['description'] = fake()->text();
        $arr['quantity'] = fake()->numberBetween(1, 10);
        $arr['price'] = fake()->numberBetween(1, 1000);
        $arr['is_available'] = fake()->boolean();
        $arr['is_featured'] = fake()->boolean();
        $arr['published_at'] = fake()->dateTime();
        return $arr;
    }
}
