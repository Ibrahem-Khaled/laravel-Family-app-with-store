<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['article', 'product'])->default('article');
            $table->string('title');
            $table->text('description')->nullable();
            $table->float('price')->nullable();
            $table->json('colors')->nullable();
            $table->json('sizes')->nullable();
            $table->json('images')->nullable();
            $table->bigInteger('quantity')->nullable();
            $table->bigInteger('views')->default(0);
            $table->timestamps();

            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
