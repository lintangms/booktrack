<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->nullable();
            $table->string('theme', 50)->default('cover-teal');
            $table->string('title');
            $table->string('author');
            $table->string('isbn');
            $table->string('category');
            $table->integer('stock')->default(0);
            $table->integer('available')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

  /**
 * Reverse the migrations.
 */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
