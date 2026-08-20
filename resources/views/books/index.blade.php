{{-- მთავარი Layout-ის გაფართოება --}}
@extends('layouts.app')

{{-- მთავარი კონტენტის სექციის დასაწყისი --}}
@section('content')

   <h1 class="mb-4 text-2xl">Books</h1>
    
   {{-- საძიებო ფორმა (GET მეთოდით) --}}
   <form method="GET" action="{{ route('books.index') }}" class="mb-4 flex items-center space-x-2">
    {{-- სათაურით ძებნის ინპუტი (ინარჩუნებს ჩაწერილი ძებნის ტექსტს) --}}
    <input type="text" name="title" placeholder="Search by title"
      value="{{ request('title') }}" class="input h-10" />
      
    {{-- ფარული ველი: ინარჩუნებს არჩეულ Tab-ს/ფილტრს ძებნის დროს --}}
    <input type="hidden" name="filter" value="{{ request('filter') }}" />
    
    <button type="submit" class="btn h-10">Search</button>
    
    {{-- ძებნისა და ფილტრების გასუფთავების ბმული --}}
    <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>
  </form>

{{-- ფილტრაციის ჩანართების (Tabs) კონტეინერი --}}
<div class="filter-container mb-4 flex">
  @php
    // ფილტრების მასივი: [URL_პარამეტრი => ეკრანზე_გამოსაჩენი_ტექსტი]
    $filters = [
        '' => 'Latest',
        'popular_last_month' => 'Popular Last Month',
        'popular_last_6months' => 'Popular Last 6 Months',
        'highest_rated_last_month' => 'Highest Rated Last Month',
        'highest_rated_last_6months' => 'Highest Rated Last 6 Months',
    ];
  @endphp

  {{-- თითოეული ფილტრის ბმულის გენერირება --}}
  @foreach ($filters as $key => $label)
    {{-- ბმული ინარჩუნებს არსებულ ძებნას (...request()->query()) და ანახლებს ფილტრს --}}
    <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}"
      {{-- ამოწმებს არის თუ არა აქტიური მიმდინარე ფილტრი (ან ნაგულისხმევი Latest) --}}
      class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}">
      {{ $label }}
    </a>
  @endforeach
</div>

   {{-- წიგნების სიის გამოტანა --}}
   <ul>
    {{-- @forelse ამოწმებს: თუ წიგნები არის, გაუშვებს ციკლს, თუ არა - გადავა @empty სექციაზე --}}
    @forelse ($books as $book)
        <li class="mb-4">
          <div class="book-item">
            <div class="flex flex-wrap items-center justify-between">
              
              {{-- წიგნის სათაური და ავტორი --}}
              <div class="w-full flex-grow sm:w-auto">
                <a href="{{ route('books.show', $book->id) }}" class="book-title">{{ $book->title }}</a>
                <span class="book-author">by {{ $book->author }}</span>
              </div>
              
              {{-- რეიტინგი და შეფასებების რაოდენობა --}}
              <div>
                {{-- საშუალო რეიტინგის დამრგვალება 1 ათწილადამდე (მაგ: 4.5) --}}
                <div class="book-rating">
                  {{ number_format($book->reviews_avg_rating, 1) }}
                </div>
                {{-- Str::plural ავტომატურად ამატებს 's'-ს თუ რაოდენობა 1-ზე მეტია (review / reviews) --}}
                <div class="book-review-count">
                  out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                </div>
              </div>

            </div>
          </div>
        </li>
    @empty
        {{-- თუ ბაზიდან წიგნი ვერ მოიძებნა --}}
        <li class="mb-4">
          <div class="empty-book-item">
            <p class="empty-text">No books found</p>
            {{-- ფილტრების ჩამოყრის/საწყის ეტაპზე დაბრუნების ბმული --}}
            <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
          </div>
        </li>
    @endforelse
   </ul>

@endsection