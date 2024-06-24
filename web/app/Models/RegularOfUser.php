<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegularOfUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'join_date'
    ];
}
