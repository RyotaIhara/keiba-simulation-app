<div class="form-group">
    <label for="how_to_buy_box">買い方を選択</label>
    <select name="how_to_buy_box" id="how_to_buy_box" class="form-control">
        @foreach ($howToBuyMstDatas as $howToBuyMstData)
            <option value="{{ $howToBuyMstData->getId() }}" @if ($howToBuyMstData->getId() == $votingRecordsIndexViewData->getHowToBuyMstId()) selected @endif>{{ $howToBuyMstData->getHowToBuyName() }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="voting_uma_ban_box">買い目（頭数分をカンマ区切りで入力）</label>
    <input type="text" name="voting_uma_ban_box" id="voting_uma_ban_box" class="form-control" value="">
</div>

<div class="form-group">
    <label for="voting_amount_box">掛け金</label>
    <input type="number" name="voting_amount_box" id="voting_amount_box" class="form-control" value="">
</div>
