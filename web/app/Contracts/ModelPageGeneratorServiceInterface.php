<?php

namespace App\Contracts;

use App\DTO\ModelPageSectionDTO;
use App\Models\OfUser;

interface ModelPageGeneratorServiceInterface
{
    public function generateTitle(OfUser $ofUser): string;
    public function generateDescription(OfUser $ofUser): string;
    public function generateH1(OfUser $ofUser): string;
    public function generateShortBiographySection(OfUser $ofUser): ModelPageSectionDTO;
    public function generateNumberOfSubscribersSection(OfUser $ofUser): ModelPageSectionDTO;
    public function generateQuantityOfContentProfileSection(OfUser $ofUser): ModelPageSectionDTO;
    public function generateEarningsSection(OfUser $ofUser): ModelPageSectionDTO;
    public function generateSocialMediaAccountsSection(OfUser $ofUser): ModelPageSectionDTO;
    public function generatePaidSubscriptionCostSection(OfUser $ofUser): ModelPageSectionDTO;
    public function generateAccessToPageWithoutPayingSection(OfUser $ofUser): ModelPageSectionDTO;
    public function generateUniqueTextSection(OfUser $ofUser): ModelPageSectionDTO;
}
