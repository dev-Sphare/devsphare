<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class Hackathon extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'slug',
        'short_description',
        'description',
        'location',
        'start_at',
        'end_at',
        'status',
        'is_paid',
        'capacity',
    ];

    protected static function booted()
    {
        // auto-generate slug from title
        static::creating(function ($hackathon) {
            $hackathon->slug = Str::slug($hackathon->title) . '-' . Str::random(5);
        });
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

}
