<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'category_id',
        'code',
        'title',
        'author',
        'publisher',
        'year',
        'description',
        'cover_image',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'year' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ebook(): HasOne
    {
        return $this->hasOne(Ebook::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function activeLoans(): HasMany
    {
        return $this->hasMany(Loan::class)->where('status', 'borrowed');
    }

    public function ebookReads(): HasMany
    {
        return $this->hasMany(EbookRead::class);
    }

    public function isPhysical(): bool
    {
        return $this->category && $this->category->slug === 'physical';
    }

    public function isEbook(): bool
    {
        return $this->category && $this->category->slug === 'ebook';
    }

    public function isAvailable(): bool
    {
        if (!$this->isPhysical()) {
            return false;
        }

        return $this->stock > 0;
    }

    public function scopePhysicals($query)
    {
        return $query->whereHas('category', fn($q) => $q->where('slug', 'physical'));
    }

    public function scopeEbooks($query)
    {
        return $query->whereHas('category', fn($q) => $q->where('slug', 'ebook'));
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('publisher', 'like', "%{$search}%");
        });
    }
}
