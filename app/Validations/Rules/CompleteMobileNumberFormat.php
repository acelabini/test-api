<?php

namespace App\Validations\Rules;

use Illuminate\Contracts\Validation\Rule;

class CompleteMobileNumberFormat implements Rule
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
        return preg_match('/(\+\d{1,3}[- ]?)?\d{10}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be in the proper format and must be between 12 and 19 characters.';
    }
}
