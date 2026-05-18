<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable(['name', 'type', 'description', 'location', 'images', 'proposer_id', 'status', 'rating', 'reviews', 'reject_reason'])]
class Solution extends Model
{
    use HasFactory;

    protected $casts = [
        'images' => 'array',
        'reviews' => 'array',
    ];

    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposer_id');
    }
}
