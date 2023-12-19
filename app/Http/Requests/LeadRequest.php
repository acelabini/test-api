<?php

namespace App\Http\Requests;

use App\Validations\Rules\CompleteMobileNumberFormat;
use App\Validations\Rules\CompleteMobileNumberFormatPH;
use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
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
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'project_id' => 'required|uuid',
            'form_data' => 'required|array',
            'form_data.which_describe_you_best' => 'required',
            'form_data.when_are_you_planning_to_buy' => 'required',
            'full_name' => 'required|string|max:140',
            'mobile' => ['required', new CompleteMobileNumberFormat(), new CompleteMobileNumberFormatPH()],
            'email' => 'required|email'
        ];
    }
}
