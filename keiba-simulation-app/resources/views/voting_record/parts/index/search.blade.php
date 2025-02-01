{{-- 検索フォーム --}}
<form method="GET" action="{{ route('voting_record.index') }}" class="p-4 border rounded mb-4">
    <h5 class="mb-3">検索条件</h5>
    <!-- レース日 -->
    <div class="form-group mb-3" style="max-width: 500px;">
        <label for="race_date">レース日</label>
        <input type="date" id="race_date" name="race_date" class="form-control" 
                value="{{ request('race_date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
    </div>

    <!-- レース場 (セレクトボックス) -->
    <div class="form-group mb-3" style="max-width: 500px;">
        <label for="racecourse">レース場</label>
        <select id="racecourse" name="racecourse" class="form-control">
            <option value="">すべて</option>
            @foreach ($raceSchedulesWithCourseDatas as $racecourseData)
                <option value="{{ $racecourseData['jyo_cd'] }}" @if ($racecourseData['jyo_cd'] == request('racecourse')) selected @endif>
                    {{ $racecourseData['racecourse_name'] }} ({{ $racecourseData['race_date'] }})
                </option>
            @endforeach
        </select>
    </div>

    <!-- レース番号 -->
    <div class="form-group mb-3" style="max-width: 500px;">
        <label for="race_number">レース番号</label>
        <input type="number" id="race_num" name="race_num" class="form-control"
                value="{{ request('race_num') }}" placeholder="例: 1～12">
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary me-2">検索</button>
        <a href="{{ route('voting_record.index') }}" class="btn btn-secondary">リセット</a>
    </div>
</form>
