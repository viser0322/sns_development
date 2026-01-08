<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
  public function __construct()
  {
    // ログイン判定
    $this->middleware('auth:user');
  }

  public static function convertLink($list)
  {
    $result_list = [];

    foreach ($list as $post) {
      //URL抽出の正規表現
      $pattern = '/https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+/';

      if (isset($post['id'])) {
      //該当する文字列に処理
      $result_list[$post['id']] = 
        preg_replace_callback($pattern,function ($matches) {
          return '<a href="'.$matches[0].'">'.$matches[0].'</a>';
        }, htmlspecialchars($post['content']));
      }
    }

    return $result_list;
  }

  public static function convertLinkObject($object)
  {
    $result_list = [];

    //URL抽出の正規表現
    $pattern = '/https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+/';

    if (isset($object->id)) {
    //該当する文字列に処理
    $result_list[$object->id] = 
      preg_replace_callback($pattern,function ($matches) {
        return '<a href="'.$matches[0].'">'.$matches[0].'</a>';
      }, htmlspecialchars($object->content));
    }

    return $result_list;
  }
}
