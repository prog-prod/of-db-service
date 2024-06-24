<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicFreeTrialSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'of_user_id'
    ];
}
