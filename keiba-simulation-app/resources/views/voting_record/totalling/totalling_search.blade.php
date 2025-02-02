{{-- 検索フォーム --}}
<form method="GET" action="{{ route('voting_record.totalling') }}" class="p-4 border rounded mb-4">
    <h5 class="mb-3">検索条件</h5>
    <!-- レース日（開始） -->
    <div class="form-group mb-3" style="max-width: 500px;">
        <label for="start_race_date">レース日（開始）</label>
        <input type="date" id="start_race_date" name="start_race_date" class="form-control" 
                value="{{ request('start_race_date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
    </div>

    <!-- レース日（終了） -->
    <div class="form-group mb-3" style="max-width: 500px;">
        <label for="end_race_date">レース日（終了）</label>
        <input type="date" id="end_race_date" name="end_race_date" class="form-control" 
                value="{{ request('end_race_date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
    </div>

    <!-- レース場 (セレクトボックス) -->
    <div class="form-group mb-3" style="max-width: 500px;">
        <label for="racecourse">レース場</label>
        <select id="racecourse" name="racecourse" class="form-control">
            <option value="">すべて</option>
            @foreach ($raceCourseDatas as $racecourseData)
                <option value="{{ $racecourseData->getJyoCd() }}" 
                    @if (($racecourseMst !== NULL) && ($racecourseData->getJyoCd() ==  $racecourseMst->getJyoCd())) selected @endif>
                        {{ $racecourseData->getRacecourseName() }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary me-2">検索</button>
        <a href="{{ route('voting_record.totalling') }}" class="btn btn-secondary">リセット</a>
    </div>
</form>
