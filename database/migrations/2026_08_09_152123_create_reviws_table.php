<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * გაეშვება ბაზის მიგრაციის დროს (ქმნის ცხრილს).
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            //  პირველადი გასაღები (Primary Key) - unsignedBigInteger ტიპის id
            $table->id();

            //  საგარეო გასაღები (Foreign Key), რომელიც აკავშირებს მიმოხილვას წიგნთან (books.id).
            // cascadeOnDelete() - წიგნის წაშლისას ავტომატურად წაშლის მის მიმოხილვებსაც.
            $table->foreignId('book_id')
                 ->constrained()
                 ->cascadeOnDelete();

            //  ტექსტური ველი მიმოხილვის შინაარსისთვის
            $table->text('review');

            //  მცირე რიცხვითი ველი (0-255) შეფასებისთვის/რეიტინგისთვის (მაგ: 1-დან 5-მდე)
            $table->unsignedTinyInteger('rating');

            //  ქმნის created_at და updated_at სვეტებს
            $table->timestamps();
        });
    }

    /**
     * გაეშვება მიგრაციის უკან დაბრუნებისას (Rollback).
     */
    public function down(): void
    {
        //  წაშლის 'reviews' ცხრილს, თუ ის არსებობს
        Schema::dropIfExists('reviews');
    }
};