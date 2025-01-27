<div class="form-group">
    <label for="how_to_buy_formaation">買い方を選択</label>
    <select name="how_to_buy_formaation" id="how_to_buy_formaation" class="form-control">
        @foreach ($howToBuyMstDatas as $howToBuyMstData)
            <option value="{{ $howToBuyMstData->getId() }}" @if ($howToBuyMstData->getId() == $votingRecordsIndexViewData->getHowToBuyMstId()) selected @endif>{{ $howToBuyMstData->getHowToBuyName() }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="voting_uma_ban">1着</label>
    <input type="text" name="voting_uma_ban_1_formaation" id="voting_uma_ban_1_formaation" class="form-control" value="">
</div>
<div class="form-group">
    <label for="voting_uma_ban">2着</label>
    <input type="text" name="voting_uma_ban_2_formaation" id="voting_uma_ban_2_formaation" class="form-control" value="">
</div>
<div class="form-group">
    <label for="voting_uma_ban">3着</label>
    <input type="text" name="voting_uma_ban_3_formaation" id="voting_uma_ban_3_formaation" class="form-control" value="">
</div>
<div class="form-group">
    <label for="voting_amount_formaation">掛け金</label>
    <input type="number" name="voting_amount_formaation" id="voting_amount_formaation" class="form-control" value="">
</div>
