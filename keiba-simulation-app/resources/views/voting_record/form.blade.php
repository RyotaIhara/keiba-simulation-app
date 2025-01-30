@php
    use Carbon\Carbon;
@endphp

<div class="form-group">
    <label for="race_schedule">レース日</label>
    <input type="text" class="form-control" value="{{ request('race_date', \Carbon\Carbon::today()->format('Y-m-d')) }}" disabled>
</div>

<div class="form-group">
    <label for="race_schedule">レース場を選択</label>
    <select name="racecourse_mst" id="racecourse_mst" class="form-control" required>
        @foreach ($raceSchedulesWithCourseDatas as $data)
            <option value="{{ $data['jyo_cd'] }}"
                @if (!is_null($votingRecord->getRaceInfo()) && $data['jyo_cd'] == $votingRecord->getRaceInfo()->getJyoCd()) selected @endif>
                {{ $data['racecourse_name'] }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="race_num">レース番号を選択</label>
    <select name="race_num" id="race_num" class="form-control" required>
        @foreach ($raceNumDatas as $raceNumData)
            <option value="{{ $raceNumData }}"
                @if (!is_null($votingRecord->getRaceInfo()) && $raceNumData == $votingRecord->getRaceInfo()->getRaceNum()) selected @endif>
                {{ $raceNumData }}R
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="how_to_buy">買い方を選択</label>
    <select name="how_to_buy" id="how_to_buy" class="form-control" required>
        @foreach ($howToBuyMstDatas as $howToBuyMstData)
            <option value="{{ $howToBuyMstData->getId() }}"
                @if (!is_null($votingRecord->getHowToBuyMst()) && $howToBuyMstData->getId() == $votingRecord->getHowToBuyMst()->getId()) selected @endif>
                {{ $howToBuyMstData->getHowToBuyName() }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="voting_uma_ban">買い目</label>
    <input type="text" name="voting_uma_ban" id="voting_uma_ban" class="form-control"
        @if (!is_null($votingRecord->getVotingUmaBan())) value="{{ $votingRecord->getVotingUmaBan() }}" @endif required />
</div>
<div class="form-group">
    <label for="voting_amount">掛け金</label>
    <input type="number" name="voting_amount" id="voting_amount" class="form-control" 
        @if (!is_null($votingRecord->getVotingAmount())) value="{{ $votingRecord->getVotingAmount() }}" @endif required />
</div>

<input type="hidden" name="race_date" value="{{ $raceDate }}" />