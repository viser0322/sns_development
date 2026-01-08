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

<form action="/regist" method="POST" enctype="multipart/form-data">
  @csrf
  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th colspan="2">基本情報</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>名前 <span class="badge badge-danger">必須</span></th>
        <td>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?: $name }}">
          @error('name')
          <span class="invalid-feedback" role="alert">{{ $message }}</span>
          @enderror
        </td>
      </tr>
      <tr>
        <th>メールアドレス <span class="badge badge-danger">必須</span></th>
        <td>
          <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') ?: $email }}">
          @error('email')
          <span class="invalid-feedback" role="alert">{{ $message }}</span>
          @enderror
        </td>
      </tr>

      <tr>
        <th>アイコン</th>
        <td>
          <input type="file" class=" @error('icon') is-invalid @enderror" id="inputFile" accept=".jpg,.jpeg,.png,.gif" name="icon">
          <!-- custom-file-input -->
          <!-- <label class="custom-file-label" for="inputFile">ファイルを選択してください</label> -->
          <span class="icon-message">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
            </svg>
          </span>
          @error('icon')
          <span class="invalid-feedback" role="alert">{{ $message }}</span>
          @enderror
        </td>
      </tr>
      <tr>
        <th>自己紹介</th>
        <td>
          <textarea name="detail" class="form-control @error('detail') is-invalid @enderror">{{ old('detail') }}</textarea>
          @error('detail')
          <span class="invalid-feedback" role="alert">{{ $message }}</span>
          @enderror
        </td>
      </tr>
      <tr>
        <th>部署</th>
        <td>
          <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department') }}">
          @error('department')
          <span class="invalid-feedback" role="alert">{{ $message }}</span>
          @enderror
        </td>
      </tr>
      <tr>
        <th>誕生日</th>
        <td>
          <input type="date" name="birthday" class="form-control @error('birthday') is-invalid @enderror" value="{{ old('birthday') }}">
          @error('birthday')
          <span class="invalid-feedback" role="alert">{{ $message }}</span>
          @enderror
        </td>
      </tr>
      <tr>
        <th>入社日</th>
        <td>
          <input type="date" name="hire_date" class="form-control @error('hire_date') is-invalid @enderror" value="{{ old('hire_date') }}">
          @error('hire_date')
          <span class="invalid-feedback" role="alert">{{ $message }}</span>
          @enderror
        </td>
      </tr>

    </tbody>
  </table>
  <div>
    <input type="submit" value="登録" class="btn btn-primary">
    <a class="btn btn-secondary" href="/login">戻る</a>
  </div>
</form>
@endsection
