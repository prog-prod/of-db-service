<?php

namespace App\Casts;

use App\DTO\SubscriptionBundleDTO;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;

class SubscriptionBundleCast implements CastsAttributes
{
    /**
     * @param $model
     * @param string $key
     * @param $value
     * @param array $attributes
     * @return Collection<SubscriptionBundleDTO>
     */
    public function get($model, string $key, $value, array $attributes): Collection
    {
        $values = json_decode($value, true);
        return collect($values)->map(fn ($value) => new SubscriptionBundleDTO($value));
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return json_encode($value);
    }
}
