@extends('layouts.app')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="container mt-4">
        <h2>投票（{{ $subTitle }}）</h2>
        <form action="{{ route('voting_record.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="race_schedule">レース日</label>
                <input type="text" class="form-control" value="{{ request('race_date', \Carbon\Carbon::today()->format('Y-m-d')) }}" disabled>
            </div>

            <div class="form-group">
                <label for="race_schedule">レース場を選択</label>
                <select name="racecourse_mst" id="racecourse_mst" class="form-control" required>
                    @foreach ($raceSchedulesWithCourseDatas as $data)
                        <option value="{{ $data['jyo_cd'] }}">
                            {{ $data['racecourse_name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="race_num">レース番号を選択</label>
                <select name="race_num" id="race_num" class="form-control" required>
                    @foreach ($raceNumDatas as $raceNumData)
                        <option value="{{ $raceNumData }}">
                            {{ $raceNumData }}R
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="how_to_buy">買い方を選択</label>
                <select name="how_to_buy" id="how_to_buy" class="form-control" required>
                    @foreach ($howToBuyMstDatas as $data)
                        <option value="{{ $data->getId() }}">
                            {{ $data->getHowToBuyName() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="betting_type">馬券を選択</label>
                <select name="betting_type" id="betting_type" class="form-control" required>
                    @foreach ($bettingTypeMstDatas as $data)
                        <option value="{{ $data->getId() }}">
                            {{ $data->getBettingTypeName() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="voting_uma_ban">買い目</label>
                <input type="text" name="voting_uma_ban" id="voting_uma_ban" class="form-control" value="" required />
            </div>
            <div class="form-group">
                <label for="voting_amount">掛け金</label>
                <input type="number" name="voting_amount" id="voting_amount" class="form-control" value="" required />
            </div>

            <input type="hidden" name="race_date" value="{{ $raceDate }}" />

            <!-- ボタン -->
            <button type="submit" class="btn btn-primary">保存する</button>
            <a href="{{ route('voting_record.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </form>
    </div>
@endsection
