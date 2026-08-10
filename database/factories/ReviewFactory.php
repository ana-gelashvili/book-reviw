<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // book_id ნაგულისხმევად ცარიელია (null), რადგან Seeder-იდან ->for($book)-ით მივაბამთ
            'book_id' => null,

            // გენერირდება შემთხვევითი ტექსტური აბზაცი მიმოხილვისთვის
            'review' => fake()->paragraph,

            //  ყურადღება: 'reting'-ის ნაცვლად უნდა ეწეროს 'rating'! (სვეტის სახელი ბაზაში)
            'rating' => fake()->numberBetween(1, 5),

            //  შექმნის თარიღი: შემთხვევითი დრო ბოლო 2 წლის ინტერვალში
            'created_at' => fake()->dateTimeBetween('-2 years'),

            //  განახლების თარიღი: შექმნის თარიღიდან დღემდე ინტერვალში
            // (ყურადღება: 'created_at'-ს $ ნიშანი სჭირდება წინ: '$created_at')
            'updated_at' => fake()->dateTimeBetween('$created_at', 'now')
        ];
    }
    
    //  სტეიტი "კარგი" შეფასებებისთვის (4 ან 5 ვარსკვლავი)
    public function good()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(4, 5)
            ];
        });
    }

    //  სტეიტი "საშუალო" შეფასებებისთვის (2, 3 ან 4 ვარსკვლავი - ჩვეულებრივ 2-4 ჯობს, 5-ის ნაცვლად)
    public function average()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(2, 4)
            ];
        });
    }

    //  სტეიტი "ცუდი" შეფასებებისთვის (1, 2 ან 3 ვარსკვლავი)
    public function bad()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(1, 3)
            ];
        });
    }

    
}
