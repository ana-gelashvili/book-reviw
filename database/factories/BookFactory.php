<?php

namespace Database\Factories;

use App\Models\Book; //  კლასის სახელი იწერება დიდი ასოთი (Book და არა book)
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * განსაზღვრავს მოდელის ნაგულისხმევ მდგომარეობას (Fake მონაცემებს).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //  წიგნის სათაური: აგენერირებს შემთხვევით 3-სიტყვიან წინადადებას
            'title' => fake()->sentence(3),

            //  ავტორის სახელი და გვარი: აგენერირებს შემთხვევით სახელს
            'author' => fake()->name,

            //  შექმნის თარიღი: აგენერირებს შემთხვევით დროს ბოლო 2 წლის ინტერვალში
            'created_at' => fake()->dateTimeBetween('-2 years'),

            //  განახლების თარიღი: აგენერირებს დროს შექმნის თარიღიდან ($created_at) დღემდე
            'updated_at' => fake()->dateTimeBetween('$created_at', 'now')
        ];
    }
}