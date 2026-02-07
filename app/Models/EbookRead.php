<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbookRead extends Model
{
    protected $fillable = [
        'book_id',
        'ip_address',
        'user_agent',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
