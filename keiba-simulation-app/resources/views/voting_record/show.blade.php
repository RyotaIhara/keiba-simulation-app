@extends('layouts.app')

@section('content')
    <h1>レース場データの詳細</h1>
    <p><strong>コード:</strong> {{ $racecourseMst->getJyoCd() }}</p>
    <p><strong>レース場名:</strong> {{ $racecourseMst->getRacecourseName() }}</p>
    <a href="{{ route('racecourse_mst.index') }}" class="btn btn-secondary">一覧に戻る</a>
    <a href="{{ route('racecourse_mst.edit', $racecourseMst->getId()) }}" class="btn btn-warning">修正する</a>
@endsection
