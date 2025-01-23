@extends('layouts.app')

@section('content')
    <h1>投票履歴</h1>

    {{-- 検索フォーム --}}
    @include('voting_record.search')

    <div class="d-flex justify-content-start align-items-center mb-4">
        {{-- 新規作成ボタン --}}
        <div class="me-3">
            <a href="{{ route('voting_record.create', ['race_date' => request('race_date', \Carbon\Carbon::today()->format('Y-m-d'))]) }}" 
               class="btn btn-primary">
                「{{ request('race_date', \Carbon\Carbon::today()->format('Y-m-d')) }}」のデータを新規作成
            </a>
        </div>
    
        {{-- 集計ボタン --}}
        <div style="margin-left: 10px;">
            <a href="{{ route('voting_record.totalling') }}" class="btn btn-success">集計</a>
        </div>
    </div>
    

    <table class="table">
        <thead>
            <tr>
                <th>レース日</th>
                <th>レース場（レース番号）</th>
                <th>レース名</th>
                <th>識別</th>
                <th>買い目</th>
                <th>投票金額</th>
                <th>払戻金</th>
                <th>的中フラグ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($votingRecordsIndexViewDatas as $votingRecord)
                <tr>
                    <td>{{ $votingRecord->getRaceDate()->format('Y-m-d') }}</td>
                    <td>
                        {{ $votingRecord->getRacecourseName() }}
                        ({{ $votingRecord->getRaceNum() }}R)
                    </td>
                    <td>{{ $votingRecord->getRaceName() }}</td>
                    <td>{{ $votingRecord->getHowToBuyName() }}</td>
                    <td>{{ $votingRecord->getVotingUmaBan() }}</td>
                    <td>{{ $votingRecord->getVotingAmount() }}</td>
                    <td>{{ $votingRecord->getRefundAmount() }}</td>
                    <td>{{$votingRecord->getHitStatus()}}</td>
                    <td>
                        @if ($votingRecord->getHitStatus() == "1") 的中
                        @elseif ($votingRecord->getHitStatus() == "2") ×
                        @else 未確定
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('voting_record.copy', $votingRecord->getVotingRecordId()) }}" class="btn btn-info">複製</a>
                        <a href="{{ route('voting_record.edit', $votingRecord->getVotingRecordId()) }}" class="btn btn-warning">修正する</a>
                        <form action="{{ route('voting_record.destroy', $votingRecord->getVotingRecordId()) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">削除する</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ページャー --}}
    @include('voting_record.pager')
@endsection
