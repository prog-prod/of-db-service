<?php

namespace App\Services;

use App\Contracts\JsonLdServiceInterface;
use App\Models\OfUser;

class JsonLdService implements JsonLdServiceInterface
{

    public function generateParams(?OfUser $ofUser, string $key): array
    {
        if(!$ofUser) {
            return [];
        }

        $result = [
            'current_url' => url()->current(),
            'current_page_key' => $key,
        ];

        $photo_of_first_result = $ofUser?->avatar_thumbs['c144'] ?? '';
        if (!empty($photo_of_first_result)) {
            $result['photo_of_first_result'] = $photo_of_first_result;
        }

        $date_published = $ofUser?->date_published->format('Y-m-d') ?? '';
        if (!empty($date_published)) {
            $result['date_published'] = $date_published;
        }
        $date_update_of_first_result = $ofUser?->updated_at->format('Y-m-d') ?? '';
        if (!empty($date_update_of_first_result)) {
            $result['date_update_of_first_result'] = $date_update_of_first_result;
        }

        return $result;
    }
}
