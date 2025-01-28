@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>ログインフォーム</h1>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="code" class="form-label">ユーザーコード</label>
                <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">パスワード</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">ログイン</button>
        </form>
    </div>
@endsection
