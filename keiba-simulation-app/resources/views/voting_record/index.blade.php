@extends('layouts.app')

@section('content')
    <h1>投票履歴</h1>
    <a href="{{ route('voting_record.create') }}" class="btn btn-primary">新規作成</a>
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
                    <td>
                        <a href="{{ route('voting_record.show', $votingRecord->getVotingRecordId()) }}" class="btn btn-info">詳細へ</a>
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
@endsection
