<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['quiz_response_id', 'question_id', 'option_id', 'essay_answer'];

    public function quizResponse()
    {
        return $this->belongsTo(QuizResponse::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
