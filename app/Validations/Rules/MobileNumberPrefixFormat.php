<?php

namespace App\Validations\Rules;

use Illuminate\Contracts\Validation\Rule;

class MobileNumberPrefixFormat implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/\+\d{1,3}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be in the proper format for mobile prefix.';
    }
}
