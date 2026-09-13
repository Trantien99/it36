<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotFaq extends Model
{
    protected $fillable = [
        'question',
        'keywords',
        'answer',
        'link_text',
        'link_url',
        'priority',
        'status',
    ];
}
