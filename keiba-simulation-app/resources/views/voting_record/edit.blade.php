@extends('layouts.app')

@section('content')
    <h1>投票</h1>
    <form action="{{ route('voting_record.update', $votingRecordsIndexViewData->getVotingRecordId()) }}" method="POST">
        @csrf
        @method('PUT')
        @include('voting_record.form')

        <!-- ボタン -->
        <button type="submit" class="btn btn-primary">保存する</button>
        <a href="{{ route('voting_record.index') }}" class="btn btn-secondary">一覧に戻る</a>
    </form>
@endsection
