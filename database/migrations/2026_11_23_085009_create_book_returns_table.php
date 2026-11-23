<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_code')->unique();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->date('return_date');
            $table->integer('late_days')->default(0);
            $table->integer('fine_amount')->default(0);
            $table->enum('condition', ['Baik', 'Rusak', 'Hilang'])->default('Baik');
            $table->enum('fine_status', ['Lunas', 'Belum Lunas', 'Bebas Denda'])->default('Bebas Denda');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_returns');
    }
};
