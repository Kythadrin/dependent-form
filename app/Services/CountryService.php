<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\CountryDTO;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use RuntimeException;

class CountryService
{
    /** @return Collection<CountryDTO> */
    public function getCountriesFromCache(): Collection
    {
        /** @var Collection<CountryDTO>|null $countries */
        $countries = Cache::get('countries_data');

        if ($countries === null) {
            throw new RuntimeException('Can\'t get countries data from cache');
        }

        if (!$countries->every(fn($item) => $item instanceof CountryDTO)) {
            throw new RuntimeException('Cached countries data is corrupted or invalid.');
        }

        return $countries;
    }

    /** @return string[] */
    public function getCountriesNames(): array
    {
        $countries = $this->getCountriesFromCache();

        /** @var string[] $countiesNames */
        $countiesNames = $countries->pluck('country')->all();

        return $countiesNames;
    }

    /** @return string[] */
    public function getLanguagesByCountry(string $countryName): array
    {
        $countries = $this->getCountriesFromCache();

        /** @var CountryDTO|null $country */
        $country = $countries->firstWhere('country', ucfirst(strtolower($countryName)));

        return $country?->languages ?? [];
    }
}
