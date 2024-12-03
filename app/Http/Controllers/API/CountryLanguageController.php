<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CountryLanguageController extends Controller
{
    public function __construct(
        private readonly CountryService $countryService,
    ) {
    }

    public function getCountries(): JsonResponse
    {
        try {
            $countries = $this->countryService->getCountriesNames();
        } catch (Exception $exception) {
            return new JsonResponse(
                $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse($countries, Response::HTTP_OK);
    }

    public function getLanguages(string $country): JsonResponse
    {
        try {
            $languages = $this->countryService->getLanguagesByCountry($country);
        } catch (Exception $exception) {
            return new JsonResponse(
                $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if (empty($languages)) {
            new JsonResponse([
                'error' => 'Country not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($languages, Response::HTTP_OK);
    }
}
