<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
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
      'email' => 'required|email|max:200|unique:users,email,'.$this->id.',id,del_flg,0',
    ];
  }

  public function messages() {
    return [
      'name.required' => '名前を入力してください',
      'name.max' => '名前は80文字以内で入力してください',
      'email.required' => 'メールアドレスを入力してください',
      'email.email' => 'メールアドレスの形式で入力してください',
      'email.max' => 'メールアドレスは200文字以内で入力してください',
      'email.unique' => 'このメールアドレスは登録されています',
    ];
  }
}
