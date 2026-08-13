<?php

namespace App\Http\Controllers;

use App\Models\Book; // შემოგვაქვს Book მოდელი
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * წიგნების სიის გამოტანა (ძებნის ფილტრით)
     */
    public function index(Request $request) // იღებს შემოსულ HTTP მოთხოვნას
    {
        // ამოვიღებთ 'title' პარამეტრს URL-იდან (მაგ: ?title=harry)
        $title = $request->input('title');

        // თუ $title არსებობს, ემატება ძებნის ფილტრი (scopeTitle), ბოლოს მოაქვს შედეგი
        $books = Book::when($title, fn($query, $title) => $query->title($title))
            ->get();

        // აბრუნებს Blade შაბლონს და გადასცემს $books ცვლადს
        return view('books.index', compact('books'));
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
