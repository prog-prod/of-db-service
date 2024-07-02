<?php

namespace App\Services;

use Illuminate\Support\Str;

class HelperService
{
    public function escapeElasticSearchValue(?string $string): string
    {
        if (!$string) {
            return '';
        }

        $specialChars = [
            '+' => '\\+',
            '-' => '\\-',
            '=' => '\\=',
            '&&' => '\\&&',
            '||' => '\\||',
            '>' => '\\>',
            '<' => '\\<',
            '!' => '\\!',
            '(' => '\\(',
            ')' => '\\)',
            '{' => '\\{',
            '}' => '\\}',
            '[' => '\\[',
            ']' => '\\]',
            '^' => '\\^',
            '"' => '\\"',
            '~' => '\\~',
            '*' => '\\*',
            '?' => '\\?',
            ':' => '\\:',
            '\\' => '\\\\',
            '/' => '\\/'
        ];

        return strtr($string, $specialChars);
    }

    function getNameFromKey($input): string
    {
        // Replace dashes with spaces
        $formatted = Str::replace('-', ' ', $input);

        // Convert to title case
        return Str::title($formatted);
    }

    function isGodMode(): bool
    {
        return (bool)request()->cookie('god1');
    }

    function getRedirectedUrls()
    {
        return [
            'eclair_ler',
            'carolina_baker',
            'tiffany.wilson',
            'helen_powers',
            'roksi_ray',
            'kattcutie',
            'gina_rock',
            'jamie_adamss',
            'remsi_starling',
            'maryhoney1',
            'diannet',
            'amanda_redrose',
            'krisss_kisss_free',
            'leah_roxxx',
            'acidmelanie_free',
            'dina_rose',
            'kris_brand',
            'cute_dina',
            'stacy.daviss',
            'lustful_light',
            'alice_lane',
            'nikki_lll',
            'ioannared',
            'nats_space',
            'berryrita',
            'jess_clark',
            'anita_nelson',
            'eva_silver',
            'lolaa_jones',
            'maria_anderson',
            'alexa_sweet',
            'xxxenia_ks',
            'amelia_alister',
            'kamila_hart',
            'olga_pierc',
            'marina_lee',
            'casey_rooks',
            'polix_cat',
            'lina_queen',
            'angelion',
            'hot_purple_queen',
            'erika_no_rules',
            'veronica_cox',
            'hot_hellen',
            'cherieloveforever',
            'harleyagreen',
            'adelina_gray',
            'stacy.daviss/vote',
            'krisss_kisss_free/vote',
            'lolaa_jones/vote',
            'tiffany.wilson/vote',
            'model/44/vote',
            'model/53/vote',
            'model/62/vote',
            'model/67/vote',
            'model/70/vote',
            'model/73/vote',
            'model/74/vote',
            'model/74/comments',
            'model/80/vote',
            'model/82/vote',
            'model/83/vote',
            'model/84/vote',
            'model/86/vote',
            'model/88/vote',
            'page/about',
            'page/contacts'
        ];
    }
}
