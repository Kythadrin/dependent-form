<?php

declare(strict_types=1);

namespace App\DTO;

class CountryDTO
{
    /** @param string[] $languages */
    public function __construct(
        public string $country,
        public array $languages,
    ) {
    }
}
