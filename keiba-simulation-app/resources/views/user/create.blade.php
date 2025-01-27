@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>ユーザー新規作成</h2>
        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            @include('user.form')

            <!-- ボタン -->
            <button type="submit" class="btn btn-primary">保存する</button>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </form>
    </div>
@endsection
