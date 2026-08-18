<?php

namespace App\Http\Controllers;

use App\Models\Book; // შემოგვაქვს Book მოდელი
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * წიგნების სიის გამოტანა (ძებნის ფილტრით)
     */
public function index(Request $request)
{
    // იღებს ძებნის სათაურს URL-იდან (თუ მითითებულია)
    $title = $request->input('title');
    
    // იღებს ფილტრს URL-იდან, თუ არ არის - მიანიჭებს ცარიელ ტექსტს ('')
    $filter = $request->input('filter', '');

    // იწყებს Query Builder-ს და სურვილისამებრ ფილტრავს სათაურით
    $books = Book::when(
        $title,
        fn($query, $title) => $query->title($title)
    );

    // match-ით ირჩევს შესაბამის Scope-ს და ამატებს არსებულ მოთხოვნას
    $books = match ($filter) {
        'popular_last_month' => $books->popularLastMonth(),
        'popular_last_6months' => $books->popularLast6Months(),
        'highest_rated_last_month' => $books->highestRatedLastMonth(),
        'highest_rated_last_6months' => $books->highestRatedLast6Months(),
        default => $books->latest(),
    };

    // მხოლოდ ახლა სრულდება მოთხოვნა ბაზაში ->get()-ის მეშვეობით
    $books = $books->get();

    // აბრუნებს index.blade.php შაბლონს წიგნების მონაცემებით
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
