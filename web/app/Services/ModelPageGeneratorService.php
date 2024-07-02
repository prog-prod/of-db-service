<?php

namespace App\Services;

use App\Contracts\ModelPageGeneratorServiceInterface;
use App\DTO\ModelPageSectionDTO;
use App\DTO\TableOfContentsDTO;
use App\Models\OfUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ModelPageGeneratorService implements ModelPageGeneratorServiceInterface
{
    private ?ModelPageSectionDTO $quantityOfContentProfileSection = null;
    private ?ModelPageSectionDTO $shortBiographySection = null;
    private ?ModelPageSectionDTO $numberOfSubscribersSection = null;
    private ?ModelPageSectionDTO $earningsSection = null;
    private ?ModelPageSectionDTO $socialMediaAccountsSection = null;
    private ?ModelPageSectionDTO $accessToPageWithoutPayingSection = null;
    private ?ModelPageSectionDTO $paidSubscriptionCostSection = null;
    public function __construct()
    {
        //
    }

    public function generateTitle(OfUser $ofUser): string
    {
        return "{$ofUser->name} OnlyFans Profile: Free Photos, Stats and Social Media";
    }

    public function generateDescription(OfUser $ofUser):string
    {
        return Cache::rememberForever("ofUserPage.$ofUser->id.description", function () use ($ofUser) {
            $name = $ofUser->name;
            $username = "@$ofUser->username";
            $photos = $ofUser->photos_count;
            $videos = $ofUser->videos_count;
            $first_part = collect([
                'Get Access',
                'Check out',
                'View',
                'Discover',
                'Get access to',
                'Visit',
                'See',
            ]);
            $second_part =  collect([
                'Account',
                'Profile',
                'Page',
            ]);
            $third_part =  collect([
                'How to get',
                'Can you get',
                'Is it possible to get',
            ]);
            $fourth_part = collect([
                'Content',
                'Content stats',
                'Stats',
                'Model activity',
                'Activity',
            ]);

            return $first_part->random()." to $username ($name) OnlyFans ".$second_part->random().". ".$third_part->random()." a Free Trial and Find All $name's Social Media? Earnings Info and Rumors. ".$fourth_part->random().": ✓ $photos Photos, ✓ $videos Videos.";
        });
    }

    public function generateH1(OfUser $ofUser): string
    {
        return $ofUser->name." OnlyFans: Photos, Bio, Social Links, Stats ".date("Y");
    }

    public function generateShortBiographySection(OfUser $ofUser): ModelPageSectionDTO
    {
        $this->shortBiographySection = new ModelPageSectionDTO('Short Biography', transform_about_text($ofUser->about));
        return $this->shortBiographySection;
    }

    public function generateNumberOfSubscribersSection(OfUser $ofUser): ModelPageSectionDTO
    {
        $this->numberOfSubscribersSection = Cache::rememberForever("ofUserPage.$ofUser->id.num_of_subscribers", function () use ($ofUser) {
            if($ofUser->estimated_subscribers_count <= 0){
                $random_number = rand(1,6);
                $text = view('model-page-generator.number-of-subscribers.zero.no-subscribers-text-'.$random_number, [
                    'username' => $ofUser->username,
                    'name' => $ofUser->name
                ])->render();
            } else if ($ofUser->estimated_subscribers_count < 100) {
                $text = view('model-page-generator.number-of-subscribers.few-subscribers-text', [
                    'username' => $ofUser->username,
                    'subscribers_count' => $ofUser->formatted_subscribers_count
                ])->render();
            } else if (100 <= $ofUser->estimated_subscribers_count && $ofUser->estimated_subscribers_count < 1000) {
                $text = view('model-page-generator.number-of-subscribers.normal-subscribers-text', [
                    'username' => $ofUser->username,
                    'name' => $ofUser->name,
                    'subscribers_count' => $ofUser->formatted_subscribers_count
                ])->render();
            } else if (1000 <= $ofUser->estimated_subscribers_count && $ofUser->estimated_subscribers_count < 10000) {
                $text = view('model-page-generator.number-of-subscribers.many-subscribers-text', [
                    'username' => $ofUser->username,
                    'name' => $ofUser->name,
                    'subscribers_count' => $ofUser->formatted_subscribers_count
                ])->render();
            } else {
                $text = view('model-page-generator.number-of-subscribers.lots-of-subscribers-text', ['username' => $ofUser->username, 'name' => $ofUser->name, 'subscribers_count' => $ofUser->formatted_subscribers_count])->render();
            }

            return new ModelPageSectionDTO("What's the Total Number of Subscribers Following @$ofUser->username?", $text);
        });
        return $this->numberOfSubscribersSection;
    }

    public function generateQuantityOfContentProfileSection(OfUser $ofUser): ModelPageSectionDTO
    {
        $this->quantityOfContentProfileSection = Cache::rememberForever("ofUserPage.$ofUser->id.quantity_of_content", function () use ($ofUser) {
            $text = view('model-page-generator.quantity-of-content', [
                'username' => $ofUser->username,
                'posts_count' => $ofUser->posts_count,
                'videos_count' => $ofUser->videos_count,
                'photos_count' => $ofUser->photos_count,
            ])->render();

            return new ModelPageSectionDTO("The Quantity of Content on the @$ofUser->username Profile: Photos, Videos, Posts", $text);
        });
        return $this->quantityOfContentProfileSection;
    }


    /**
     *
     * @param OfUser $ofUser
     * @return ModelPageSectionDTO
     */
    public function generateEarningsSection(OfUser $ofUser): ModelPageSectionDTO
    {
        $this->earningsSection = Cache::rememberForever("ofUserPage.$ofUser->id.earnings", function () use ($ofUser) {
            if(!$ofUser->has_income){
                $text = view('model-page-generator.earnings.no-income', [
                    'username' => $ofUser->username,
                    'posts_count' => $ofUser->posts_count,
                    'videos_count' => $ofUser->videos_count,
                    'photos_count' => $ofUser->photos_count,
                ])->render();
            } else {
                $text = view('model-page-generator.earnings.has-income', [
                    'username' => $ofUser->username,
                    'posts_count' => $ofUser->posts_count,
                    'videos_count' => $ofUser->videos_count,
                    'photos_count' => $ofUser->photos_count,
                    'subscribers_count' => $ofUser->formatted_subscribers_count,
                    'pre_tax_year' => $ofUser->pre_tax_year,
                    'pre_tax_month' => $ofUser->pre_tax_month,
                    'month_income_from' => $ofUser->estimated_month_income->from,
                    'month_income_to' => $ofUser->estimated_month_income->to,
                    'year_income_from' => $ofUser->estimated_year_income->from,
                    'year_income_to' => $ofUser->estimated_year_income->to,
                    'subscribe_price' => $ofUser->subscribe_price,
                ])->render();
            }

            return new ModelPageSectionDTO("How Much Does ".ucfirst($ofUser->username)." Earn on OnlyFans? 💵", $text);
        });

        return $this->earningsSection;
    }

    public function generateSocialMediaAccountsSection(OfUser $ofUser): ModelPageSectionDTO
    {
        $this->socialMediaAccountsSection = Cache::rememberForever("ofUserPage.$ofUser->id.social_media_accounts", function () use ($ofUser) {
            $links = $ofUser->getSocialLinks();
            if(!empty($links)) {
                $text = view('model-page-generator.social-media-accounts.has-accounts', [
                    'username' => $ofUser->username,
                    'social_networks' => $links,
                ])->render();
            } else {
                $text = view('model-page-generator.social-media-accounts.no-accounts', [
                    'username' => $ofUser->username,
                    'name' => $ofUser->name,
                ])->render();
            }
            return new ModelPageSectionDTO("How to Find ".ucfirst($ofUser->username)."'s Social Media Accounts? 📣", $text);
        });
        return $this->socialMediaAccountsSection;
    }

    public function generateAccessToPageWithoutPayingSection(OfUser $ofUser): ModelPageSectionDTO
    {
        $ttl = Carbon::now()->addMinutes(60*24*3)->diffInSeconds();
        $this->accessToPageWithoutPayingSection = Cache::remember("ofUserPage.$ofUser->id.trial_access", $ttl,function () use ($ofUser) {
            if($ofUser->free_trial_link) {
                $text = view('model-page-generator.access-to-page-without-paying.has-trial', [
                    'username' => $ofUser->username,
                    'trial_link' => $ofUser->free_trial_link,
                    'name' => $ofUser->name,
                ])->render();
            } else {
                $text = view('model-page-generator.access-to-page-without-paying.no-trial', [
                    'username' => $ofUser->username,
                ])->render();
            }

            return new ModelPageSectionDTO(ucfirst($ofUser->username)."'s Free OnlyFans Account - Can I Access to @$ofUser->username ($ofUser->name) Page Without Paying?", $text);
        });

        return $this->accessToPageWithoutPayingSection;
    }

    public function generateUniqueTextSection(OfUser $ofUser): ModelPageSectionDTO
    {
        return Cache::rememberForever("ofUserPage.$ofUser->id.additional_info", function () use ($ofUser) {
            $titles = collect([
                "What rumors circulate about @$ofUser->username ($ofUser->name) among her fans? 💬",
                "What whispers surround @$ofUser->username among her followers? 💬",
                "Curious about the gossip surrounding @$ofUser->username? Fans spill the beans! 💬",
                "Discover the buzz about @$ofUser->username among subscribers 💬",
                "Unveiling the speculations about @$ofUser->username ($ofUser->name) among her subscribers. 💬",
                "What are fans saying about @$ofUser->username? Dive into the rumors! 💬",
                "Explore the chatter among followers about @$ofUser->username. 💬",
                "Join the conversation: What's the talk about @$ofUser->username ($ofUser->name) among fans? 💬",
                "Catch up on the latest rumors swirling around @$ofUser->username's subscribers 💬",
                "Curiosity piqued: Find out the rumors circulating around @$ofUser->username and her fans. 💬"
            ]);
            $randomNum = rand(1, 5);
            $text = view("model-page-generator.additional-info.variant-$randomNum", [
                'username' => $ofUser->username,
                'name' => $ofUser->name,
            ])->render();

            return new ModelPageSectionDTO($titles->random(), $text);
        });
    }

    public function generatePaidSubscriptionCostSection(OfUser $ofUser): ModelPageSectionDTO
    {
        $this->paidSubscriptionCostSection = Cache::rememberForever("ofUserPage.$ofUser->id.paid_subscription_cost", function () use ($ofUser) {
            $text = '';
            if(!$ofUser->is_free) {
                $text = view('model-page-generator.paid-subscription-cost', [
                    'username' => $ofUser->username,
                    'subscribe_price' => $ofUser->subscribe_price,
                    'six_month_subscription_cost' => $ofUser->six_month_subscription_cost,
                    'year_subscription_cost' => $ofUser->year_subscription_cost,
                ])->render();
            }
            return new ModelPageSectionDTO("How Much Does a Paid Subscription Cost for @$ofUser->username's OnlyFans", $text);
        });
        return $this->paidSubscriptionCostSection;
    }

    /**
     * @return TableOfContentsDTO[]
     */
    public function getTableOfContents(): array
    {
        $contents = [];
        foreach ([
                     '#biography' => $this->shortBiographySection,
                     '#numberOfSubscribers' => $this->numberOfSubscribersSection,
                     '#quantityOfContentProfile' => $this->quantityOfContentProfileSection,
                     '#earnings' => $this->earningsSection,
                     '#how_to_get_free_access' => $this->accessToPageWithoutPayingSection,
                     '#how_to_get_in_touch' => $this->socialMediaAccountsSection,
                     '#paidSubscriptionCost' => $this->paidSubscriptionCostSection,
                 ] as $href => $item) {
            if($item && !empty($item->h2) && !empty($item->text)) {
                $contents[] = new TableOfContentsDTO(href: $href, title: $item->h2);
            }
        }
        return $contents;
    }
}
