<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleUpdateRequest extends FormRequest
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
      'category_id' => 'sometimes|uuid|exists:article_categories,id',
      'title' => 'sometimes|string|max:100',
      'slug' => 'sometimes|string',
      'content' => 'sometimes|string|max:250',
      'status' => 'sometimes|string',
    ];
  }
}
