@extends('layout')

@section('title')
{{ $title }}
@endsection

@section('page_css')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
@endsection

@section('page_js')
@endsection

@section('content')
<h2>{{ $title }}</h2>
<span class="text-danger">{{ session('error_msg') }}</span>

  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th colspan="2">基本情報</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>名前</th>
        <td>
          {{ $user->name }}
        </td>
      </tr>
      <tr>
        <th>メールアドレス</th>
        <td>
          {{ $user->email }}
        </td>
      </tr>

      <tr>
        <th>アイコン</th>
        <td>
          @if(isset($user->icon))
          <img src="{{ asset('storage/content/'.$user->icon) }}" alt="" style="width: 400px">
          @else
          登録なし
          @endif
        </td>
      </tr>
      <tr>
        <th>自己紹介</th>
        <td>
          {{ $user->detail }}
        </td>
      </tr>
      <tr>
        <th>部署</th>
        <td>
          {{ $user->department }}
        </td>
      </tr>
      <tr>
        <th>誕生日</th>
        <td>
          {{ $user->birthday }}
        </td>
      </tr>
      <tr>
        <th>入社日</th>
        <td>
          {{ $user->hire_date }}
        </td>
      </tr>

    </tbody>
  </table>
  <div>
    <li class="headerNaviList__item"><a href="/edit/{{ $user->id }}" class="headerNaviList__link">編集</a></li>
  </div>
  @endsection
  