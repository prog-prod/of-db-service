<?php

namespace App\Console\Commands;

use App\Models\OfTag;
use Illuminate\Console\Command;

class CheckTopKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:topKeys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check top categories';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ofinderTopKeys = [
            'free-onlyfans',
            'best-onlyfans',
            'asian-onlyfans',
            'foot-fetish-onlyfans',
            'australian-onlyfans',
            'big-tits-onlyfans',
            'best-onlyfans-girls',
            'redhead-onlyfans',
            'onlyfans-nude',
            'german-onlyfans',
            'milf-onlyfans',
            'pornstar-onlyfans',
            'indian-onlyfans',
            'hottest-onlyfans',
            'ebony-onlyfans',
            'teen-onlyfans',
            'lesbian-onlyfans',
            'celebrities-with-onlyfans',
            'onlyfans-uk',
            'onlyfans-xxx',
            'gay-onlyfans',
            'big-ass-onlyfans',
            'irish-onlyfans',
            'onlyfans-couples',
            'trans-onlyfans',
            'bbw-onlyfans',
            'amateur-onlyfans',
            '18-year-old-onlyfans',
            'scottish-onlyfans',
            'latina-onlyfans',
            'onlyfans-sex',
            'mom-daughter-onlyfans',
            'onlyfans-creampie',
            'cuckold-onlyfans',
            'korean-onlyfans',
            'japanese-onlyfans',
            'cosplay-onlyfans',
            'instagram-models-onlyfans',
            'best-male-onlyfans',
            'onlyfans-near-me',
            'blonde-onlyfans',
            'tiktok-onlyfans',
            'onlyfans-porn',
            'onlyfans-sexting',
        ];

        $cats = OfTag::query()->whereIn('name', $ofinderTopKeys)->get();
        dd($cats->pluck('name')->toArray(), count($cats));
    }
}
