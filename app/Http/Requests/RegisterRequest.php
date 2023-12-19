<?php

namespace App\Http\Requests;

use App\Validations\Rules\CompleteMobileNumberFormat;
use App\Validations\Rules\MobileNumberPrefixFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: For now we make this true since we don't have authorization yet
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:200',
            'middle_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|unique:App\Models\User,email',
            'mobile_prefix' => ['required', new MobileNumberPrefixFormat()],
            'mobile_number' => ['required', new CompleteMobileNumberFormat()],
            'password' => ['required', Password::min(8)],
            'role_id' => 'required|uuid|exists:App\Models\Role,id'
        ];

    }
}
