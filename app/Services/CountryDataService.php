<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\CountryDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class CountryDataService
{
    const string COUNTRY_DATA_FILE_PATH = 'app/private/country-by-languages.json';

    public function loadAndValidateData(): Collection
    {
        $json = file_get_contents(storage_path(self::COUNTRY_DATA_FILE_PATH));

        if ($json === false) {
            throw new RuntimeException("Unable to read the JSON file by path: ".storage_path(self::COUNTRY_DATA_FILE_PATH).".");
        }

        $countryData = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("Invalid JSON format: " . json_last_error_msg());
        }

        $validator = Validator::make(['countries' => $countryData], [
            'countries' => 'required|array',
            'countries.*.country' => 'required|string',
            'countries.*.languages' => 'required|array',
            'countries.*.languages.*' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return collect(array_map(
            fn($item) => new CountryDTO($item['country'], $item['languages']),
            $countryData
        ));
    }
}
