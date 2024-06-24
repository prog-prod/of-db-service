<?php

namespace App\Enums;

use App\Enums\Traits\EnumTrait;

enum OfUserSocialNetworksEnum: string
{
    use EnumTrait;

    case FACEBOOK = 'Facebook';
    case INSTAGRAM = 'Instagram';
    case TIKTOK = 'TikTok';
    case TWITTER = 'Twitter';
    case YOUTUBE = 'YouTube';
    case REDDIT = 'Reddit';
    case ONLYFANS = 'OnlyFans';
    case SNAPCHAT = 'Snapchat';
    case TELEGRAM = 'Telegram';

    public static function urlMapping(): array
    {
        return [
            'facebook.com' => self::FACEBOOK,
            'instagram.com' => self::INSTAGRAM,
            'tiktok.com' => self::TIKTOK,
            'twitter.com' => self::TWITTER,
            'youtube.com' => self::YOUTUBE,
            'reddit.com' => self::REDDIT,
            'onlyfans.com' => self::ONLYFANS,
            'snapchat.com' => self::SNAPCHAT,
            'telegram.org' => self::TELEGRAM,
        ];
    }

    public static function getNetworkByUrl(string $url): ?self {
        foreach (self::urlMapping() as $domain => $network) {
            if (str_contains($url, $domain)) {
                return $network;
            }
        }
        return null;
    }

    public function getLink(): ?string {
        $mapping = self::urlMapping();
        $domain = array_search($this, $mapping, true);
        return $domain ? "https://www.$domain" : null;
    }
}
