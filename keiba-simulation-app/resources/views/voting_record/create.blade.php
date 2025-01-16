@extends('layouts.app')

@section('content')
    <h1>レース場データ作成</h1>
    <form action="{{ route('voting_record.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="jyo_cd">コード</label>
            <input type="text" name="jyo_cd" id="jyo_cd" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="racecourse_name">レース場名</label>
            <input type="text" name="racecourse_name" id="racecourse_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">保存する</button>
        <a href="{{ route('voting_record.index') }}" class="btn btn-secondary">一覧に戻る</a>
    </form>
@endsection
