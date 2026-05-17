<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Quiz extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'cover_image', 'slug',
        'is_active', 'primary_key_label', 'primary_key_enabled',
        'primary_key_unique', 'time_limit',
    ];

    protected $casts = ['is_active' => 'boolean', 'primary_key_enabled' => 'boolean', 'primary_key_unique' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($quiz) {
            if (empty($quiz->slug)) {
                $quiz->slug = self::generateSlug();
            }
        });
    }

    public static function generateSlug(): string
    {
        do {
            $slug = Str::upper(Str::random(7));
        } while (self::where('slug', $slug)->exists());
        return $slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function responses()
    {
        return $this->hasMany(QuizResponse::class);
    }

    public function getPublicUrlAttribute(): string
    {
        return route('public.quiz.show', $this->slug);
    }
}
