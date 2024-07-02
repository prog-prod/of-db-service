<?php

namespace App\Contracts;

use App\DTO\MetaTagsDTO;

interface MetaTagGeneratorServiceInterface
{
    public function generateMetaTags(string $keyword): MetaTagsDTO;
    public function getMetaTagsForSearchPage(string $keyword): MetaTagsDTO;
    public function getMetaTagsForNewPage(): MetaTagsDTO;
    public function getMetaTagsForTopPage(): MetaTagsDTO;
    public function getMetaTagsForFreePage(): MetaTagsDTO;

}
