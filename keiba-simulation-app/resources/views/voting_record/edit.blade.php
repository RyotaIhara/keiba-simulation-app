@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>投票（{{ $subTitle }}）</h2>
        <form action="{{ route('voting_record.update', $votingRecord->getId()) }}" method="POST">
            @csrf
            @method('PUT')
            @include('voting_record.form')

            <!-- ボタン -->
            <button type="submit" class="btn btn-primary">保存する</button>
            <a href="{{ route('voting_record.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </form>
    </div>
@endsection
