<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable(['title', 'description', 'category', 'banner_image', 'proposer_id', 'status', 'start_date', 'end_date', 'participants', 'goal', 'impact_metrics', 'reviews', 'reject_reason'])]
class Campaign extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'participants' => 'array',
        'reviews' => 'array',
    ];

    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposer_id');
    }
}
