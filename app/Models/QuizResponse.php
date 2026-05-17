<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResponse extends Model
{
    protected $fillable = ['quiz_id', 'respondent_name', 'primary_key_value', 'ip_address', 'submitted_at'];
    protected $casts = ['submitted_at' => 'datetime'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
