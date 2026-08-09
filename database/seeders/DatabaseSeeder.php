<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;   
use App\Models\Review; 
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. კარგი მიმოხილვების მქონე წიგნები (33 ცალი)
        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()
                ->count($numReviews)
                ->good()
                ->for($book)
                ->create();
        });

        // 2. საშუალო მიმოხილვების მქონე წიგნები (34 ცალი)
        Book::factory(34)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()
                ->count($numReviews)
                ->average()
                ->for($book)
                ->create();
        });

        // 3. ცუდი მიმოხილვების მქონე წიგნები (33 ცალი)
        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()
                ->count($numReviews)
                ->bad()
                ->for($book)
                ->create();
        });
    } // <--- run() ფუნქციის დახურვა აი აქ უნდა იყოს!
}