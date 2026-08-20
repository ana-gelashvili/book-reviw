<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * წიგნების სიის გამოტანა (ძებნის ფილტრით და ქეშირებით)
     */
   public function index(Request $request)
    {
        $title = $request->input('title', '');
        $filter = $request->input('filter', '');

        // 1. ცარიელი ფილტრის დროს ნაგულისხმევ გასაღებად ავიღოთ 'latest'
        $filterKey = $filter ?: 'latest';
        $cacheKey = 'books:' . $filterKey . ':' . $title;

        // 2. ქეშირება
        $books = cache()->remember($cacheKey, 3600, function () use ($title, $filter) {
            $query = Book::when(
                $title,
                fn($q, $title) => $q->title($title)
            );

            return match ($filter) {
                'popular_last_month' => $query->popularLastMonth()->get(),
                'popular_last_6months' => $query->popularLast6Months()->get(),
                'highest_rated_last_month' => $query->highestRatedLastMonth()->get(),
                'highest_rated_last_6months' => $query->highestRatedLast6Months()->get(),
                default => $query->latest()->withAvgRating()->withReviewsCount()->get(),
            };
        });

        return view('books.index', ['books' => $books]);
    
    
  }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * კონკრეტული წიგნის გამოტანა (ქეშირებით)
     */
   public function show(Book $book)
 {
    $cacheKey = 'book:' . $book->id;

    $book = cache()->remember($cacheKey, 3600, function () use ($book) {
        return $book->load([
            'reviews' => fn($query) => $query->latest()
        ])->loadAvg('reviews', 'rating')
          ->loadCount('reviews');
    });

    return view('books.show', ['book' => $book]);
  }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
