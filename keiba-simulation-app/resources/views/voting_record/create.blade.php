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
                <label for="betting_type">馬券を選択</label>
                <select name="betting_type" id="betting_type" class="form-control" required>
                    @foreach ($bettingTypeMstDatas as $data)
                        <option value="{{ $data->getId() }}">
                            {{ $data->getBettingTypeName() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 投票方法ごとに記入する内容を変更 -->
            <div class="form-group">
                <label for="how_to_buy">買い方を選択</label><br>
                <input type="radio" name="how_to_buy" id="normal" value="Normal" checked>
                <label for="normal">通常</label><br>
                <input type="radio" name="how_to_buy" id="box" value="Box">
                <label for="box">ボックス</label><br>
                <input type="radio" name="how_to_buy" id="nagashi" value="Nagashi">
                <label for="nagashi">ながし</label><br>
                <input type="radio" name="how_to_buy" id="formation" value="Formation">
                <label for="formation">フォーメーション</label>
            </div>

            <div id="dynamic-content">
                <div id="Normal" class="template-content">
                    @include('voting_record.parts.create.normal')
                </div>
                <div id="Box" class="template-content" style="display: none;">
                    @include('voting_record.parts.create.box')
                </div>
                <div id="Nagashi" class="template-content" style="display: none;">
                    @include('voting_record.parts.create.nagashi')
                </div>
                <div id="Formation" class="template-content" style="display: none;">
                    @include('voting_record.parts.create.formation')
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // ラジオボタンの変更イベントを監視
                    document.querySelectorAll('input[name="how_to_buy"]').forEach(function (radio) {
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

            <input type="hidden" name="race_date" value="{{ $raceDate }}" />

            <!-- ボタン -->
            <button type="submit" class="btn btn-primary">保存する</button>
            <a href="{{ route('voting_record.index') }}" class="btn btn-secondary">一覧に戻る</a>
        </form>
    </div>
@endsection
