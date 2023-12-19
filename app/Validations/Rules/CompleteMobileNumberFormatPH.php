<?php

namespace App\Validations\Rules;

use Illuminate\Contracts\Validation\Rule;

class CompleteMobileNumberFormatPH implements Rule
{
    const PATTERN = '/^((\+63)([0-9]){10}$)/';
    const PH_COUNTRY_CODE_PATTERN = '/^(\+63)/';
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (preg_match(self::PH_COUNTRY_CODE_PATTERN, $value)) {
            return !empty(preg_match(self::PATTERN, $value));
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Philippine mobile numbers must only have 10 digits (e.g. 9123456789).';
    }
}
