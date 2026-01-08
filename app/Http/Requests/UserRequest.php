<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'name' => 'required|max:80',
      'email' => 'required|email|max:255',
      'detail' => 'max:280',
      'department' => 'max:50',
    ];
  }

  public function messages() {
    return [
      'name.required' => '名前を入力してください',
      'name.max' => '名前は80文字以内で入力してください',
      'email.required' => 'メールアドレスを入力してください',
      'email.email' => 'メールアドレスの形式で入力してください',
      'email.max' => 'メールアドレスは255文字以内で入力してください',
      'detail.max' => '自己紹介は280文字以内で入力してください',
      'department.max' => '部署は50文字以内で入力してください',
    ];
  }
}
