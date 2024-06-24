<?php

namespace App\Models;

use App\Contracts\SearchableModel;
use App\Models\Traits\ElasticScoutTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class OfTag extends Model implements SearchableModel
{
    use HasFactory;
    use Searchable;
    use ElasticScoutTrait;

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
        'title',
        'description',
        'h1'
    ];

    public function users()
    {
        return $this->belongsToMany(OfUser::class, 'of_tag_of_user', 'of_tag_id', 'of_user_id');
    }

    public function groups()
    {
        return $this->belongsToMany(OfTagsGroup::class, 'of_tags_of_tags_groups', 'of_tag_id', 'of_tags_group_id')->withTimestamps();
    }
}
