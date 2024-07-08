<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfTag extends Model
{

    public function getRouteKeyName(): string
    {
        return 'key';
    }

    protected $fillable = [
        'key',
        'name',
        'results',
        'traffic',
        'hidden',
    ];

    //Relations
    public function users()
    {
        return $this->belongsToMany(OfUser::class, 'of_tag_of_user', 'of_tag_id', 'of_user_id');
    }

}
