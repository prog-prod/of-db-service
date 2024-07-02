<?php

namespace App\Services;

use App\Contracts\MetaTagGeneratorServiceInterface;
use App\DTO\MetaTagsDTO;
use App\Enums\SpecialCategoriesEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MetaTagGeneratorService implements MetaTagGeneratorServiceInterface
{
    const MAX_TITLE_LENGTH = 65;
    const STATIC_H1 = "2024's ".self::KEYWORD_NAME." OnlyFans content creators";
    const STATIC_TITLE = "2024's ".self::KEYWORD_NAME." OnlyFans models | OnlyGirls.com";
    const STATIC_DESC = "Discover must-follow ".self::KEYWORD_NAME." OnlyFans accounts with exclusive content. Selected from over 2 million girls. Discover more similar Free and Paid accounts in the &quot;".self::KEYWORD_NAME."&quot; category.";

    protected string $partWithKeyword;
    const KEYWORD_NAME = '{KEYWORD}';
    private Collection $pinkWordsStack;
    protected Collection $pinkWords;

    public function __construct()
    {
        $this->partWithKeyword = self::KEYWORD_NAME . ' OnlyFans';
        $this->pinkWords = collect([
            'accounts',
            'girls',
            'models',
            'pages',
            'content creators',
            'profiles'
        ]);
        $this->pinkWordsStack = collect();
    }

    public function generateMetaTags(string $keyword): MetaTagsDTO
    {
        $this->resetThirdPartStack();

        $title = $this->generateTitle($keyword);
        $description = $this->generateDescription($keyword);
        $h1 = $this->generateH1($keyword);

        return new MetaTagsDTO(title: $title, description: $description, h1: $h1);
    }

    private function generateTitle(string $keyword, bool $lastPart = true): string
    {
        $result = collect([
            $selectedFirstPart = collect([
                '100 Best',
                'Top 50',
                'Top 200: Best',
                'Best',
                '100+',
                'Top 300 Hottest',
                '2024\'s'
            ])->random(), // 1 part.
            $this->getPartWithKeyword($keyword), // 2 part.
            $selectedPinkWord = $this->pinkWords->reject(fn ($value) =>
                $this->pinkWordsStack->contains($value)
            )->random() // 3 part.
        ]);

        $this->pinkWordsStack->add($selectedPinkWord);

        // 4 part.
        if ($selectedFirstPart !== '2024\'s') {
            $result->add(collect([
                'in 2024',
                'to follow in 2024',
                'of 2024',
                'worth subscribing to',
                '2024',
                '(2024)'
            ])->random());
        }

        if($lastPart){
            // 5 part.
            $result->add(collect([
                '| OnlyGirls.com',
                '| onlygirls.com',
                '- onlygirls.com',
            ])->random());
        }

        $lengthWithLastPart = Str::length($result->implode(' '));

        if ($lengthWithLastPart > self::MAX_TITLE_LENGTH + (self::MAX_TITLE_LENGTH * .2)) {
            $result->pop();
        }

        return collect($result)->implode(' ');
    }

    private function generateDescription(string $keyword): string
    {
        return collect([
            $this->generateFirstDescriptionSentence($keyword),
            $this->generateSecondDescriptionSentence(),
            $this->generateThirdDescriptionSentence($keyword)
        ])->implode('. ');
    }

    private function generateFirstDescriptionSentence(string $keyword): string
    {
        $firstPart = collect([
            'Find',
            'Discover',
            'Explore',
            'See',
            'Search for'
        ]);
        $secondPart = collect([
            'the best',
            'the hottest',
            'must-follow',
            'first-rate',
            'top-notch',
            '' // can be empty
        ]);
        $fifthPart = collect([
            'with exclusive content',
            'worth subscribing to',
            'you shouldn\'t miss',
            'to follow',
        ]);

        $result = collect([
            $firstPart->random(), // 1.
            $secondPart->random(), // 2.
            $this->getPartWithKeyword($keyword), // 3.
            $forthPart = $this->pinkWords->reject(fn ($value) =>
                $this->pinkWordsStack->contains($value)
            )->random(), // 4.
            $fifthPart->random() // 5.
        ]);

        $this->pinkWordsStack->add($forthPart);

        return collect($result)->implode(' ');
    }

    private function generateSecondDescriptionSentence(): string
    {
        $result = collect([
            collect([
                'Сarefully chosen from',
                'Chosen from',
                'Selected from',
                'Curated from',
                'Handpicked from'
            ])->random(), // 1.
            collect(['among', 'over'])->random(), // 2.
            $this->generateThirdPartSecondSentenceDescription(), // 3.
            $this->pinkWords->reject(fn ($value) =>
                $this->pinkWordsStack->contains($value)
            )->random() // 4.
        ]);
        return collect($result)->implode(' ');
    }

    private function generateThirdPartSecondSentenceDescription(): string
    {
        $num = rand(2, 4);
        $num .= collect(['+', ''])->random();
        $num .= collect([' M', ' million'])->random();
        return $num;
    }

    private function generateThirdDescriptionSentence(string $keyword): string
    {
        return Str::replace(
            self::KEYWORD_NAME,
            $keyword,
            collect([
                "Category &quot;" . self::KEYWORD_NAME . "&quot; reviewed & rated by Onlygirls.com team.",
                "Rankings in the category &quot;" . self::KEYWORD_NAME . "&quot; are updated daily.",
                "Discover more similar Free and Paid accounts in the &quot;" . self::KEYWORD_NAME . "&quot; category.",
                "Find more similar Free and Paid accounts in the &quot;" . self::KEYWORD_NAME . "&quot; category.",
                "Our experts update the &quot;" . self::KEYWORD_NAME . "&quot; category on a daily basis.",
                "Additional free and premium accounts within the &quot;" . self::KEYWORD_NAME . "&quot; category."
            ])->random());
    }

    private function getPartWithKeyword(string $keyword): string
    {
        return Str::replace(
            self::KEYWORD_NAME,
            $keyword,
            $this->partWithKeyword
        );
    }

    private function resetThirdPartStack(): void
    {
        $this->pinkWordsStack = collect();
    }

    private function generateH1(string $keyword): string
    {
        return $this->generateTitle($keyword, false);
    }

    public function getMetaTagsForSearchPage(string $keyword): MetaTagsDTO
    {
        return new MetaTagsDTO(title: Str::replace(
            self::KEYWORD_NAME,
            $keyword,
            self::STATIC_TITLE
        ), description: Str::replace(
            self::KEYWORD_NAME,
            $keyword,
            self::STATIC_DESC
        ), h1: Str::replace(
            self::KEYWORD_NAME,
            $keyword,
            self::STATIC_H1
        ));
    }
    public function getMetaTagsForNewPage(): MetaTagsDTO
    {
        $title = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::NEW), self::STATIC_TITLE);
        $desc = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::NEW), self::STATIC_DESC);
        $h1 = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::NEW), self::STATIC_H1);
        return new MetaTagsDTO(title: $title, description: $desc, h1: $h1);

    }
    public function getMetaTagsForTopPage(): MetaTagsDTO
    {
        $title = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::TOP), self::STATIC_TITLE);
        $desc = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::TOP), self::STATIC_DESC);
        $h1 = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::TOP), self::STATIC_H1);
        return new MetaTagsDTO(title: $title, description: $desc, h1: $h1);
    }
    public function getMetaTagsForFreePage(): MetaTagsDTO
    {
        $title = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::FREE), self::STATIC_TITLE);
        $desc = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::FREE), self::STATIC_DESC);
        $h1 = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::FREE), self::STATIC_H1);
        return new MetaTagsDTO(title: $title, description: $desc, h1: $h1);
    }

    public function getMetaTagsForMainPage(): MetaTagsDTO
    {
        $title = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::FREE), self::STATIC_TITLE);
        $desc = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::FREE), self::STATIC_DESC);
        $h1 = Str::replace(self::KEYWORD_NAME, __('main.categories.'.SpecialCategoriesEnum::FREE), self::STATIC_H1);
        return new MetaTagsDTO(title: $title, description: $desc, h1: $h1);
    }
}
