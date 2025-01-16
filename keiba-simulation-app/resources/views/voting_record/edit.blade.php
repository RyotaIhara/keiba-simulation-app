@extends('layouts.app')

@section('content')
    <h1>レース場データを更新する</h1>
    <form action="{{ route('voting_record.update', $votingRecord->getId()) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="jyo_cd">コード</label>
            <input type="text" name="jyo_cd" id="jyo_cd" class="form-control" value="{{ $votingRecord->getJyoCd() }}" required>
        </div>
        <div class="form-group">
            <label for="racecourse_name">レース場名</label>
            <input type="text" name="racecourse_name" id="racecourse_name" class="form-control" value="{{ $votingRecord->getRacecourseName() }}" required>
        </div>
        <button type="submit" class="btn btn-primary">更新する</button>
        <a href="{{ route('voting_record.index') }}" class="btn btn-secondary">一覧に戻る</a>
    </form>
@endsection
