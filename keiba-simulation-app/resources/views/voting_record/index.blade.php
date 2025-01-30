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

        {{-- 特殊方式での新規作成ボタン --}}
        <div style="margin-left: 10px;">
            <a href="{{ route('voting_record.createSpecialMethod', ['race_date' => request('race_date', \Carbon\Carbon::today()->format('Y-m-d'))]) }}" 
               class="btn btn-info">
               特殊方式でデータを新規作成
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
                    <td>{{ $votingRecord['race_date'] }}</td>
                    <td>
                        {{ $votingRecord['racecourse_name'] }}
                        ({{ $votingRecord['race_num'] }}R)
                    </td>
                    <td>{{ $votingRecord['race_name'] }}</td>
                    <td>{{ $votingRecord['how_to_buy_mst_id'] }}</td>
                    <td>{{ $votingRecord['voting_uma_ban'] }}</td>
                    <td>{{ $votingRecord['voting_amount'] }}</td>
                    <td>{{ $votingRecord['refund_amount'] }}</td>
                    <td>
                        @if ($votingRecord['hit_status'] == "1") 的中
                        @elseif ($votingRecord['hit_status'] == "2") ×
                        @else 未確定
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('voting_record.copy', $votingRecord['id']) }}" class="btn btn-info">複製</a>
                        <a href="{{ route('voting_record.edit', $votingRecord['id']) }}" class="btn btn-warning">修正する</a>
                        <form action="{{ route('voting_record.destroy', $votingRecord['id']) }}" method="POST" style="display:inline;">
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
