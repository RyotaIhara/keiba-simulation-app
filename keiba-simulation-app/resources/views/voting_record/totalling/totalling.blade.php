@extends('layouts.app')

@section('content')
    <?php $difference = $totalRefundAmount - $totalVotingAmount ?>

    <div class="container mt-4">
        <h1 class="mb-4">集計</h1>

        @include('voting_record.totalling.totalling_search')

        {{-- 投票一覧へボタン --}}
        <div style="margin-left: 10px;">
            <a href="
                {{ route('voting_record.index', [
                    'race_date' => request('end_race_date', \Carbon\Carbon::today()->format('Y-m-d'))
                ])}}" 
                class="btn btn-success">投票一覧へ</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    {{ $racecourseMst == NULL ? 'すべてのレース場' : $racecourseMst->getRacecourseName() }}
                </h4>
            </div>
            <div class="card-body">
                <!-- 対象期間を強調 -->
                <div class="alert alert-info text-center" role="alert">
                    対象期間：<strong>{{ $fromRaceDate }} ～ {{ $toRaceDate }}</strong>
                </div>

                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>項目</th>
                            <th class="text-end">金額</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>投票金の合計</td>
                            <td class="text-end">{{ number_format((int) $totalVotingAmount, 0) }}円</td>
                        </tr>
                        <tr>
                            <td>払戻金の合計</td>
                            <td class="text-end">{{ number_format((int) $totalRefundAmount, 0) }}円</td>
                        </tr>
                        <tr>
                            <td>収支の差</td>
                            <td class="text-end">
                                {{ $difference > 0 ? '+' . number_format($difference, 0) : number_format($difference, 0) }}円
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
