<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfTagsGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'order',
        'title',
        'description',
    ];

    public function getRouteKeyName()
    {
        return 'key';
    }


    public function scopeExceptLocations($query)
    {
        return $query->whereNotIn('key', ['state', 'region', 'country', 'city', 'locations']);
    }

    public function scopeOnlyLocations($query)
    {
        return $query->whereIn('key', ['state', 'region', 'country', 'city', 'locations']);
    }

    public function tags()
    {
        return $this->belongsToMany(OfTag::class, 'of_tags_of_tags_groups', 'of_tags_group_id', 'of_tag_id')->withTimestamps();
    }

    public static function withLimitedTags($limit = 10, $onlyLocations = false)
    {
        $groups = self::query()->withCount('tags');
        if($onlyLocations) {
            $groups = $groups->onlyLocations();
        } else {
            $groups = $groups->exceptLocations();
        }
        $groups = $groups->orderBy('order')->get();

        $groups->each(function ($group) use ($limit) {
            $group->setRelation('tags', $group->tags()->orderBy('results', 'desc')->limit($limit)->get());
        });

        return $groups;
    }

    public function isLocation()
    {
        return in_array($this->key, ['state', 'region', 'country', 'city', 'locations']);
    }
}
