<?php

declare(strict_types=1);

namespace App\DTO\Input;

use App\DTO\CountryDTO;
use App\Services\CountryDataService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegistrationInput
{
    /**
     * @throws ValidationException
     */
    public function __construct(
        public ?string $name = '',
        public ?string $email = '',
        public ?string $country = '',
        public ?string $language = '',
        public ?string $password = '',
        public ?string $passwordConfirmation = '',
    ) {
        $countryDataService = new CountryDataService();
        $this->validate($countryDataService->loadAndValidateData());
    }

    /**
     * @param Collection<CountryDTO> $countryData
     * @throws ValidationException
     */
    private function validate(Collection $countryData): void
    {
        $validator = Validator::make($this->toArray(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|required_with:passwordConfirmation|same:passwordConfirmation',
            'country' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($countryData) {
                    $countries = $countryData->pluck('country')->toArray();
                    if (!in_array($value, $countries, true)) {
                        $fail('The selected country is invalid.');
                    }
                },
            ],
            'language' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($countryData) {
                    $languages = $countryData->firstWhere('country', $this->country)->languages ?? [];
                    if (!in_array($value, $languages, true)) {
                        $fail('The selected language does not match the selected country.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /** @param array{
     *     name: string,
     *     email: string,
     *     country: string,
     *     language: string,
     *     password: string,
     *     passwordConfirmation: string
     * } $data
     * @throws ValidationException
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['country'],
            $data['language'],
            $data['password'],
            $data['passwordConfirmation']
        );
    }

    /** @return array{
     *     name: string,
     *     email: string,
     *     country: string,
     *     language: string,
     *     password: string,
     *     passwordConfirmation: string
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'country' => $this->country,
            'language' => $this->language,
            'password' => $this->password,
            'passwordConfirmation' => $this->passwordConfirmation,
        ];
    }
}
