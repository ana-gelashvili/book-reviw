<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Local Scope: საძიებო ფუნქციონალი სათაურის მიხედვით
    public function scopeTitle(Builder $query, string $title): Builder
    {
       return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    // 1. ყველაზე პოპულარული (ყველაზე მეტი შეფასებით + დროის ფილტრით)
    public function scopePopular(Builder $query, $from = null, $to = null): Builder | QueryBuilder
    {
        return $query->withCount([
            'reviews' => fn (Builder $q) => static::dateRangeFilter($q, $from, $to)
        ])->orderBy('reviews_count', 'desc');
    }

    // 2. მაღალრეიტინგიანი (საშუალო ქულით + დროის ფილტრით)
    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder | QueryBuilder
    {
        return $query->withAvg([
            'reviews' => fn (Builder $q) => static::dateRangeFilter($q, $from, $to)
        ], 'rating')->orderBy('reviews_avg_rating', 'desc');
    }
  

     // Local Scope: წიგნების გაფილტვრა შეფასებების მინიმალური რაოდენობის მიხედვით.
     // შენიშვნა: აქ იწერება HAVING და არა WHERE, რადგან 'reviews_count' არის withCount()-ით 
     // დინამიურად დათვლილი მნიშვნელობა. SQL-ში დათვლილი/აგრეგატული მონაცემების გასაფილტრად 
    // აუცილებელია HAVING-ის გამოყენება.
      public function scopeMinReviews(Builder $query, int $minReviews): Builder | QueryBuilder
          {
           return $query->groupBy('id')->having('reviews_count', '>=', $minReviews);
           }       



    // 3. შიდა, დამხმარე მეთოდი (DRY - ლოგიკის არ გამეორებისთვის)
    private static function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    
    // ბოლო 1 თვის პოპულარული წიგნები (მინიმუმ 2 შეფასებით)
   
    public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder
   {
    return $query->popular(now()->subMonth(), now())      // 1. ითვლის ბოლო 1 თვის მიმოხილვებს და ახდენს სორტირებას რაოდენობით
                ->highestRated(now()->subMonth(), now())  // 2. ითვლის საშუალო ქულას (გამოიყენება მეორად სორტირებად)
                ->minReviews(2);                          // 3. ტოვებს მხოლოდ იმ წიგნებს, რომელთაც აქვთ მინიმუმ 2 შეფასება
   }
   

   
  //  ბოლო 6 თვის პოპულარული წიგნები (მინიმუმ 5 შეფასებით)
 
   public function scopePopularLast6Months(Builder $query): Builder|QueryBuilder
  {
    return $query->popular(now()->subMonths(6), now())     // 1. ითვლის ბოლო 6 თვის მიმოხილვებს და ახდენს სორტირებას რაოდენობით
                ->highestRated(now()->subMonths(6), now()) // 2. ითვლის საშუალო ქულას (გამოიყენება მეორად სორტირებად)
                ->minReviews(5);                         // 3. ტოვებს მხოლოდ იმ წიგნებს, რომელთაც აქვთ მინიმუმ 5 შეფასება
  } 
   
  
  //  ბოლო 1 თვის ყველაზე მაღალრეიტინგული წიგნები (მინიმუმ 2 შეფასებით)
 
public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder
   {
    return $query->highestRated(now()->subMonth(), now()) // 1. ითვლის ბოლო 1 თვის საშუალო ქულას და ახდენს სორტირებას რეიტინგით (მაღალიდან დაბლისკენ)
                ->popular(now()->subMonth(), now())      // 2. ითვლის მიმოხილვების რაოდენობას (გამოიყენება მეორად სორტირებად, თუ ქულები ტოლია)
                ->minReviews(2);                          // 3. ტოვებს მხოლოდ იმ წიგნებს, რომელთაც აქვთ მინიმუმ 2 შეფასება
    }

    
  //  ბოლო 6 თვის ყველაზე მაღალრეიტინგული წიგნები (მინიმუმ 5 შეფასებით)
 
public function scopeHighestRatedLast6Months(Builder $query): Builder|QueryBuilder
  {
    return $query->highestRated(now()->subMonths(6), now()) // 1. ითვლის ბოლო 6 თვის საშუალო ქულას და ახდენს სორტირებას რეიტინგით (მაღალიდან დაბლისკენ)
                ->popular(now()->subMonths(6), now())      // 2. ითვლის მიმოხილვების რაოდენობას (გამოიყენება მეორად სორტირებად, თუ ქულები ტოლია)
                ->minReviews(5);                          // 3. ტოვებს მხოლოდ იმ წიგნებს, რომელთაც აქვთ მინიმუმ 5 შეფასება
  }
}