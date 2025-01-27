@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>ユーザーリスト</h1>
        <a href="{{ route('user.create') }}" class="btn btn-primary">新規作成</a>
        <table class="table">
            <thead>
                <tr>
                    <th>コード</th>
                    <th>ユーザー名</th>
                    <th>パスワード</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->getCode() }}</td>
                        <td>{{ $user->getUserName() }}</td>
                        <td>{{ $user->getPassword() }}</td>
                        <td>
                            <a href="{{ route('user.edit', $user->getId()) }}" class="btn btn-warning">修正する</a>
                            <form action="{{ route('user.destroy', $user->getId()) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">削除する</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
