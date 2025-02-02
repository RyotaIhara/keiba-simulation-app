@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center text-primary">投票内容の詳細</h1>

        <!-- 投票内容テーブル -->
        <div class="table-container">
            <table class="table table-bordered table-voting-record">
                <tbody>
                    <tr>
                        <th>レース日</th>
                        <td>{{ $votingRecord->getRaceInfo()->getRaceDate()->format('Y/m/d') }}</td>
                    </tr>
                    <tr>
                        <th>レース場</th>
                        <td>{{ $racecourseMst->getRacecourseName() }}</td>
                    </tr>
                    <tr>
                        <th>レース番号</th>
                        <td>{{ $votingRecord->getRaceInfo()->getRaceNum() }}R</td>
                    </tr>
                    <tr>
                        <th>レース名</th>
                        <td>{{ $votingRecord->getRaceInfo()->getRaceName() }}</td>
                    </tr>
                    <tr>
                        <th>出馬数</th>
                        <td>{{ $votingRecord->getRaceInfo()->getEntryHorceCount() }}頭</td>
                    </tr>
                    <tr>
                        <th>買い方</th>
                        <td>{{ $votingRecord->getHowToBuyMst()->getHowToBuyName() }}</td>
                    </tr>
                    <tr>
                        <th>馬券の種類</th>
                        <td>{{ $votingRecord->getBettingTypeMst()->getBettingTypeName() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-5">
            <h3 class="text-success">内訳</h3>
            <div>
                <a href="{{ route('voting_record.index', ['race_date' => $votingRecord->getRaceInfo()->getRaceDate()->format('Y-m-d')]) }}" class="btn btn-primary">
                   一覧へ
                </a>
                <a href="{{ route('voting_record.editVotingAmount', ['id' => $votingRecord->getId()]) }}" class="btn btn-info">
                    投票金額のまとめて更新
                </a>
                <form action="{{ route('voting_record.destroy', ['id' => $votingRecord->getId()]) }}" method="POST" class="d-inline" onsubmit="return confirm('本当に削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">投票データの削除</button>
                </form>
            </div>
        </div>

        <!-- 内訳テーブル -->
        <table class="table table-bordered table-voting-record-detail">
            <thead>
                <tr>
                    <th>買い目</th>
                    <th>投票金額</th>
                    <th>払戻金</th>
                    <th>的中フラグ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($votingRecord->getVotingRecordDetails() as $detail)
                    <tr>
                        <td>{{ $detail->getVotingUmaBan() }}</td>
                        <td>{{ number_format((int) $detail->getVotingAmount(), 0) }}円</td>
                        <td>{{ number_format((int) $detail->getRefundAmount(), 0) }}円</td>
                        <td>
                            @if ($detail->getHitStatus() == "1") 
                                <span class="text-success font-weight-bold">的中</span>
                            @elseif ($detail->getHitStatus() == "2") 
                                <span class="text-danger">×</span>
                            @else 
                                <span class="text-danger">未確定</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        .custom-orange-btn {
            background-color: #ff8800; /* オレンジ色 */
            color: white;
            border: none;
        }
        .custom-orange-btn:hover {
            background-color: #e67600; /* ホバー時の色 */
        }
    </style>
@endsection
