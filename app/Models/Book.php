<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'theme',
        'title',
        'author',
        'isbn',
        'category',
        'stock',
        'available',
        'description',
    ];

    protected static function booted()
    {
        static::creating(function ($book) {
            if (empty($book->code)) {
                $words = preg_split('/\s+/', trim($book->title));
                $code = '';
                foreach (array_slice($words, 0, 2) as $word) {
                    $code .= mb_substr($word, 0, 1);
                }
                $book->code = strtoupper($code) ?: 'BK';
            }

            if (empty($book->theme)) {
                $themes = ['cover-teal', 'cover-amber', 'cover-blue', 'cover-emerald'];
                $book->theme = $themes[array_rand($themes)];
            }
        });
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
