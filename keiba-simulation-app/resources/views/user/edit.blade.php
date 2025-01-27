@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>ユーザーデータを更新する</h1>
        <form action="{{ route('user.update', $user->getId()) }}" method="POST">
            @csrf
            @method('PUT')
            @include('user.form')

            <button type="submit" class="btn btn-primary">更新する</button>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </form>
    </div>
@endsection
