<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'review',
        'rating',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
     /**
     * Eloquent Model Events — მოდელის სიცოცხლის ციკლის მოვლენებზე რეაგირება
     */
    protected static function booted()
    {
        // 1. Eloquent Event: როცა არსებული მიმოხილვა განახლდება (Updated), იშლება ამ წიგნის ქეში
        static::updated(fn(Review $review) => cache()->forget('book:' . $review->book_id));

        // 2. Eloquent Event: როცა ახალი მიმოხილვა დაემატება (Created), იშლება ამ წიგნის ქეში
        static::created(fn(Review $review) => cache()->forget('book:' . $review->book_id));

        // 3. Eloquent Event: როცა მიმოხილვა წაიშლება (Deleted), იშლება ამ წიგნის ქეში
        static::deleted(fn(Review $review) => cache()->forget('book:' . $review->book_id));
    }
}