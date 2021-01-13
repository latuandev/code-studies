<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_set_code',
        'question',
        'ans_a',
        'ans_b',
        'ans_c',
        'ans_d',
        'ans_correct',
    ];
}
