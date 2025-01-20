@extends('layouts.app')

@section('content')
    <h1>投票</h1>
    <form action="{{ route('voting_record.update', $votingRecordsIndexViewData->getVotingRecordId()) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="race_schedule">レース場を選択</label>
            <select name="racecourse_mst" id="racecourse_mst" class="form-control" required>
                @foreach ($raceSchedulesWithCourseDatas as $data)
                    <option value="{{ $data['jyo_cd'] }}" @if ($data['jyo_cd'] == $votingRecordsIndexViewData->getJyoCd()) selected @endif>{{ $data['racecourse_name'] }} ({{ $data['race_date'] }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="race_num">レース番号を選択</label>
            <select name="race_num" id="race_num" class="form-control" required>
                @foreach ($raceNumDatas as $raceNumData)
                    <option value="{{ $raceNumData }}" @if ($raceNumData == $votingRecordsIndexViewData->getRaceNum()) selected @endif>{{ $raceNumData }}R</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="how_to_buy">買い方を選択</label>
            <select name="how_to_buy" id="how_to_buy" class="form-control" required>
                @foreach ($howToBuyMstDatas as $howToBuyMstData)
                    <option value="{{ $howToBuyMstData->getId() }}" @if ($howToBuyMstData->getId() == $votingRecordsIndexViewData->getHowToBuyMstId()) selected @endif>{{ $howToBuyMstData->getHowToBuyName() }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="voting_uma_ban">買い目</label>
            <input type="text" name="voting_uma_ban" id="voting_uma_ban" class="form-control" value="{{$votingRecordsIndexViewData->getVotingUmaBan()}}" required>
        </div>
        <div class="form-group">
            <label for="voting_amount">掛け金</label>
            <input type="number" name="voting_amount" id="voting_amount" class="form-control" value="{{$votingRecordsIndexViewData->getVotingAmount()}}" required>
        </div>

        <input type="hidden" name="race_date" value="{{ $raceDate }}" />

        <!-- ボタン -->
        <button type="submit" class="btn btn-primary">保存する</button>
        <a href="{{ route('voting_record.index') }}" class="btn btn-secondary">一覧に戻る</a>
    </form>
@endsection
