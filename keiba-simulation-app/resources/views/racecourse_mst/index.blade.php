@extends('layouts.app')

@section('content')
    <h1>レース場リスト</h1>
    <a href="{{ route('racecourse_mst.create') }}" class="btn btn-primary">新規作成</a>
    <table class="table">
        <thead>
            <tr>
                <th>コード</th>
                <th>レース場名</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($racecourseMsts as $racecourseMst)
                <tr>
                    <td>{{ $racecourseMst->getJyoCd() }}</td>
                    <td>{{ $racecourseMst->getRacecourseName() }}</td>
                    <td>
                        <a href="{{ route('racecourse_mst.show', $racecourseMst->getId()) }}" class="btn btn-info">詳細へ</a>
                        <a href="{{ route('racecourse_mst.edit', $racecourseMst->getId()) }}" class="btn btn-warning">修正する</a>
                        <form action="{{ route('racecourse_mst.destroy', $racecourseMst->getId()) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">削除する</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
