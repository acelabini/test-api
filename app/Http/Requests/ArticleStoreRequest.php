<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleStoreRequest extends FormRequest
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
      'category_id' => 'required|uuid|exists:article_categories,id',
      'title' => 'required|string|max:100',
      'slug' => 'required|string',
      'content' => 'required|string|max:250',
      'status' => 'required|string',
    ];
  }
}
