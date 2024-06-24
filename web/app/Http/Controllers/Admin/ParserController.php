<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\ParserServiceInterface;
use App\Http\Controllers\Controller;

class ParserController extends Controller
{
    public function syncParserStatuses(ParserServiceInterface $parserService) {
        return $parserService->syncParserStatuses();
    }
    public function startParsingModels(ParserServiceInterface $parserService) {
        return $parserService->startParsingModels();
    }
    public function stopParsingModels(ParserServiceInterface $parserService) {
        return $parserService->stopParsingModels();
    }
    public function startUpdatingModels(ParserServiceInterface $parserService) {
        return $parserService->startUpdatingModels();
    }
    public function stopUpdatingModels(ParserServiceInterface $parserService) {
        return $parserService->stopUpdatingModels();
    }
    public function startCheckingRegulars(ParserServiceInterface $parserService) {
        return $parserService->startCheckingRegulars();
    }
    public function stopCheckingRegulars(ParserServiceInterface $parserService) {
        return $parserService->stopCheckingRegulars();
    }
}
