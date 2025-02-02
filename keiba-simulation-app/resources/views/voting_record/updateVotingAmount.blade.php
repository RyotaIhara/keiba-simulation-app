@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center text-primary">投票金額の更新</h1>

        <form action="{{ route('voting_record.updateVotingAmount', $votingRecord->getId()) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="race_schedule">レース日</label>
                <input type="text" class="form-control" value="{{ $votingRecord->getRaceInfo()->getRaceDate()->format('Y/m/d') }}" disabled>
            </div>

            <div class="form-group">
                <label for="race_schedule">レース場</label>
                <input type="text" class="form-control" value="{{ $racecourseMst->getRacecourseName() }}" disabled>
            </div>

            <div class="form-group">
                <label for="race_schedule">レース番号</label>
                <input type="text" class="form-control" value="{{ $votingRecord->getRaceInfo()->getRaceNum() }}R" disabled>
            </div>

            <div class="form-group">
                <label for="race_schedule">買い方</label>
                <input type="text" class="form-control" value="{{ $votingRecord->getHowToBuyMst()->getHowToBuyName() }}" disabled>
            </div>

            <div class="form-group">
                <label for="race_schedule">馬券の種別</label>
                <input type="text" class="form-control" value="{{ $votingRecord->getBettingTypeMst()->getBettingTypeName() }}" disabled>
            </div>

            <div class="form-group">
                <label for="race_schedule">現在の投票金額</label>
                <input type="text" class="form-control" value="{{ $votingRecord->getVotingRecordDetails()[0]->getVotingAmount() }}" disabled>
            </div>

            <div class="form-group">
                <label for="update_voting_amount">更新予定の投票金額</label>
                <input type="number" name="update_voting_amount" id="update_voting_amount" class="form-control" value="" />
            </div>

            <!-- ボタン -->
            <button type="submit" class="btn btn-primary">更新する</button>
            <a href="{{ route('voting_record.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </form>
    </div>
@endsection
