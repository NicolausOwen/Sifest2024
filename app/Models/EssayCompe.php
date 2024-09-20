<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayCompe extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'team_name',
        'email',

        'name1',
        'phone_number1',
        'line1',
        'institution1',
        'idcard1',

        'name2',
        'phone_number2',
        'line2',
        'institution2',
        'idcard2',

        'payment',
        'submission',
        'verification',
    ];
}
