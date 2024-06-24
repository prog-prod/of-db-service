<?php
if (!function_exists('rm_tags')) {
    function rm_tags(?string $text): string
    {
        if (!$text) {
            return '';
        }

        return preg_replace_callback('/&amp;|&hellip;/', function ($matches) {
            $replacements = [
                '&amp;' => '&',
                '&hellip;' => '…',
            ];
            return $replacements[$matches[0]] ?? $matches[0];
        }, strip_tags($text));
    }

}
if (!function_exists('shorten_text')) {
    function shorten_text(?string $text, $targetLength = 100): string
    {
        if (!$text) {
            return '';
        }

        $targetLength = max(1, $targetLength);

        if (strlen($text) > $targetLength) {
            return substr($text, 0, $targetLength) . '...';
        }

        return $text;
    }
}
if (!function_exists('has_links')) {
    function has_links(?string $text): int
    {
        if (!$text) {
            return 0;
        }
        return preg_match('/\bhttps?:\/\/\S+/i', $text);
    }
}
if (!function_exists('transform_about_text')) {
    function transform_about_text(?string $aboutText): array|false|string|null
    {
        if (!is_null($aboutText) && has_links($aboutText)) {
            $whitelist = config('app.redirect_allowed_domains');
            $pattern = '/<a href="([^"]+)"[^>]*>([^<]+)<\/a>/';
            //$matches:
            //(
            //    [0] => Array
            //        (
            //            [0] => <a href="https://Loveladynina.com" target="_blank">Loveladynina.com</a>
            //            [1] => <a href="https://Worshipladynina.com" target="_blank">Worshipladynina.com</a>
            //        )
            //
            //    [1] => Array
            //        (
            //            [0] => https://Loveladynina.com
            //            [1] => https://Worshipladynina.com
            //        )
            //
            //    [2] => Array
            //        (
            //            [0] => Loveladynina.com
            //            [1] => Worshipladynina.com
            //        )
            //)
            $result = preg_match_all($pattern, $aboutText, $matches);
            if ($result) {

                foreach ($matches[0] as $key => $fullMatch) {
                    $url = $matches[1][$key];
                    $text_url = $matches[2][$key];
                    $host = parse_url($url, PHP_URL_HOST);
                    $host_text_url = parse_url($text_url, PHP_URL_HOST);

                    // Проверка на белый список и поддомены
                    $allowed = false;
                    foreach ($whitelist as $allowedDomain) {
                        if (str_ends_with($host, $allowedDomain) && ($host_text_url && str_ends_with($host_text_url, $allowedDomain) || !$host_text_url)) {
                            $allowed = true;
                            break;
                        }
                    }
                    if ($allowed) {
                        $url = $host_text_url ? $text_url : $url;
                        $newUrl = route('users-of.redirect-to-external', ['url' => $url]);
                        $newLink = '<a href="' . $newUrl . '" target="_blank">' . $matches[2][$key] . '</a>';
                        $aboutText = str_replace($fullMatch, $newLink, $aboutText);
                    } else {
                        // Удаление ссылки
                        $aboutText = str_replace($fullMatch, '', $aboutText);
                    }
                }
            }
        }
        return $aboutText;
    }
}
