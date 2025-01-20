@extends('layouts.app')

@section('content')
    <h1>投票データの詳細</h1>
    <p><strong>レース場名:</strong> {{ $votingRecordsIndexViewData->getRaceDate()->format('Y-m-d') }}</p>
    <p><strong>レース場（レース番号）:</strong> {{ $votingRecordsIndexViewData->getRacecourseName() }}（{{ $votingRecordsIndexViewData->getRaceNum() }}R）</p>
    <p><strong>レース名:</strong> {{ $votingRecordsIndexViewData->getRaceName() }}</p>
    <p><strong>識別:</strong> {{ $votingRecordsIndexViewData->getHowToBuyName() }}</p>
    <p><strong>買い目:</strong> {{ $votingRecordsIndexViewData->getVotingUmaBan() }}</p>
    <p><strong>投票金額:</strong> {{ $votingRecordsIndexViewData->getVotingAmount() }}</p>
    <p><strong>払戻金:</strong> {{ $votingRecordsIndexViewData->getRefundAmount() }}</p>
    <a href="{{ route('voting_record.index') }}" class="btn btn-secondary">一覧に戻る</a>
    <a href="{{ route('voting_record.edit', $votingRecordsIndexViewData->getVotingRecordId()) }}" class="btn btn-warning">修正する</a>
@endsection
