@extends('layouts.app')

@section('content')
    <h1>投票履歴</h1>

    {{-- 検索フォーム --}}
    @include('voting_record.parts.index.search')

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
            <a href="
                {{ route('voting_record.totalling', [
                    'start_race_date' => request('race_date', \Carbon\Carbon::today()->format('Y-m-d')),
                    'end_race_date' => request('race_date', \Carbon\Carbon::today()->format('Y-m-d')),
                ])}}" 
                class="btn btn-success">集計</a>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>レース場</th>
                <th>レース番号</th>
                <th>買い方</th>
                <th>馬券の種類</th>
                <th>買い目</th>
                <th>投票金額</th>
                <th>払戻金</th>
                <th>的中フラグ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($votingRecordsIndexViewDatas as $votingRecord)
                <tr>
                    <td>{{ $votingRecord['racecourse_name'] }}</td>
                    <td>{{ $votingRecord['race_num'] }}R</td>
                    <td>{{ $votingRecord['how_to_buy_name'] }}</td>
                    <td>{{ $votingRecord['betting_type_name'] }}</td>
                    <td>{{ $votingRecord['voting_uma_ban'] }}</td>
                    <td>{{ number_format((int) $votingRecord['voting_amount'], 0) }}円</td>
                    <td>{{ number_format((int) $votingRecord['refund_amount'], 0) }}円</td>
                    <!-- 的中フラグ -->
                    @if ($votingRecord['hit_status'] == "1") 
                        <td><span class="text-success font-weight-bold">的中</span></td>
                    @elseif ($votingRecord['hit_status'] == "2") 
                        <td>×</td>
                    @else 
                        <td>未確定</td>
                    @endif
                    <td>
                        <a href="{{ route('voting_record.show', $votingRecord['id']) }}" class="btn btn-info">詳細</a>
                        <a href="{{ route('voting_record.create', $votingRecord['id']) }}" class="btn btn-warning">複製</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ページャー --}}
    @include('voting_record.parts.index.pager')
@endsection
