<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('catalog_products')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->unsignedTinyInteger('rating');
            $table->text('body');
            $table->boolean('approved')->default(false);
            $table->timestamps();

            $table->index(['product_id', 'approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_reviews');
    }
};
