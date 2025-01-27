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
            <option value="{{ $data['jyo_cd'] }}" @if ($data['jyo_cd'] == $votingRecordsIndexViewData->getJyoCd()) selected @endif>
                {{ $data['racecourse_name'] }} ({{ $data['race_date'] }})
            </option>
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

<input type="hidden" name="race_date" value="{{ $raceDate }}" />


<!-- 投票方法ごとに記入する内容を変更 -->
<div class="form-group">
    <label for="how_to_vote">投票方法を選択</label><br>
    <input type="radio" name="how_to_vote" id="nagashi" value="nagashiTemplate" checked>
    <label for="nagashi">流し</label><br>
    <input type="radio" name="how_to_vote" id="box" value="boxTemplate">
    <label for="box">ボックス</label><br>
    <input type="radio" name="how_to_vote" id="formation" value="formationTemplate">
    <label for="formation">フォーメーション</label>
</div>

<div id="dynamic-content">
    <div id="nagashiTemplate" class="template-content">
        @include('voting_record.specialMethod.nagashi')
    </div>
    <div id="boxTemplate" class="template-content" style="display: none;">
        @include('voting_record.specialMethod.box')
    </div>
    <div id="formationTemplate" class="template-content" style="display: none;">
        @include('voting_record.specialMethod.formation')
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ラジオボタンの変更イベントを監視
        document.querySelectorAll('input[name="how_to_vote"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                const selectedValue = this.value; // 選択されたラジオボタンの値を取得

                // すべてのテンプレートを非表示
                document.querySelectorAll('.template-content').forEach(function (template) {
                    template.style.display = 'none';
                });

                // 選択されたテンプレートのみ表示
                const selectedTemplate = document.getElementById(selectedValue);
                if (selectedTemplate) {
                    selectedTemplate.style.display = 'block';
                }
            });
        });
    });
</script>
