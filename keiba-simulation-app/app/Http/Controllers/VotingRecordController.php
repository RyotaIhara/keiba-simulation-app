<?php

namespace App\Http\Controllers;

use App\Entities\VotingRecordsIndexView;
use App\Services\Crud\VotingRecordService;
use App\Services\Crud\RaceScheduleService;
use App\Services\Crud\RaceInfoService;
use App\Services\Crud\HowToBuyMstService;
use App\Services\Crud\RacecourseMstService;
use App\Services\General\VotingRecordGeneral;
use App\Services\View\VotingRecordsIndexViewService;
use Illuminate\Http\Request;
use DateTime;

class VotingRecordController extends Controller
{
    private $votingRecordService;
    private $votingRecordsIndexViewService;
    private $raceScheduleService;
    private $howToBuyMstService;
    private $raceInfoService;
    private $racecourseMstService;
    private $votingRecordGeneral;

    const DEFAULT_PAZE = 1;
    const RACE_NUM_DATAS = [1,2,3,4,5,6,7,8,9,10,11,12];

    public function __construct()
    {
        $this->votingRecordService = app(VotingRecordService::class);
        $this->votingRecordsIndexViewService = app(VotingRecordsIndexViewService::class);
        $this->raceScheduleService = app(RaceScheduleService::class);
        $this->howToBuyMstService = app(HowToBuyMstService::class);
        $this->raceInfoService = app(RaceInfoService::class);
        $this->racecourseMstService = app(RacecourseMstService::class);
        $this->votingRecordGeneral = app(VotingRecordGeneral::class);
    }

    /** 投票一覧（日付ごと） **/
    public function index(Request $request)
    {
        $page = $request->query('page', self::DEFAULT_PAZE);
        $pageSize = $request->query('page_size', config('config.INDEX_PAGE_SIZE'));

        // 検索項目としてわたってくるパラメータ
        $raceDate = $request->query('race_date', date('Y-m-d'));
        $raceNum =$request->query('racecourse_mst', NULL);
        $racecourse = $request->query('race_num', NULL);

        // 検索項目でレース場一覧の取得が必要な箇所があるので対応
        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst([
            'raceDate' => $raceDate
        ]);

        # 一覧に表示するデータを取得
        $searchForm = [
            'raceDate' => $raceDate,
            'raceNum' => $raceNum,
            'racecourse' => $racecourse
        ];
        $tmpResult = $this->votingRecordsIndexViewService->getAllVotingRecordsIndexViewDatas($page, $pageSize, $searchForm);

        return view('voting_record.index', [
            'votingRecordsIndexViewDatas' => $tmpResult['data'],
            'totalItems' => $tmpResult['totalItems'],
            'pageSize' => $pageSize,
            'currentPage' => $tmpResult['currentPage'],
            'totalPages' => $tmpResult['totalPages'],
            //検索フォーム関連
            'raceDate' => $raceDate,
            'raceNum' => $raceNum,
            'racecourse' => $racecourse,
            'raceSchedulesWithCourseDatas' => $raceSchedulesWithCourseDatas,
        ]);
    }

    /** 日付ごとの集計記録 **/
    public function totalling(Request $request)
    {
        # 集計対象のデータを取得
        // 検索項目としてわたってくるパラメータ
        $fromRaceDate = $request->query('start_race_date', date('Y-m-d'));
        $toRaceDate = $request->query('end_race_date', date('Y-m-d'));
        $racecourse = $request->query('racecourse', NULL);

        // 日付以外でクエリのwhere句に入る項目
        $otherWhereParams = [
            'jyoCd' => $racecourse,
        ];
        $totallingDatas = $this->votingRecordService->getVotingRecordByFromToDate($fromRaceDate, $toRaceDate, $otherWhereParams);

        # 取得したデータをもとに集計する
        $totalVotingAmount = 0;
        $totalRefundAmount = 0;
        foreach ($totallingDatas as $votingRecord) {
            $totalVotingAmount += $votingRecord->getVotingAmount();
            $totalRefundAmount += $votingRecord->getRefundAmount();
        }

        return view('voting_record.totalling.totalling', [
            'totalVotingAmount' => $totalVotingAmount,
            'totalRefundAmount' => $totalRefundAmount,
            //検索フォーム関連
            'fromRaceDate' => $fromRaceDate,
            'toRaceDate' => $toRaceDate,
            'racecourseMst' => $racecourse = NULL ? NULL : $this->racecourseMstService->getRacecourseMstByUniqueColumn(['jyoCd' => $racecourse]),
            'raceCourseDatas' => $this->racecourseMstService->getAllRacecourseMsts(),
        ]);
    }

    /** 通常方式でのデータ作成（create） **/
    public function create(Request $request)
    {
        // 投票する対象日を取得
        $raceDate = $request->query('race_date', date('Y-m-d'));

        // フォーム項目でレース場一覧とレース数の取得が必要な箇所があるので対応
        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst([
            'raceDate' => $raceDate
        ]);
        $howToBuyMstDatas = $this->howToBuyMstService->getAllHowToBuyMsts();
        $raceNumDatas = self::RACE_NUM_DATAS;

        // テンプレに渡す項目
        $votingRecordsIndexViewData = new VotingRecordsIndexView();
        $params = [
            'subTitle' => '新規作成',
            'raceSchedulesWithCourseDatas' => $raceSchedulesWithCourseDatas,
            'howToBuyMstDatas' => $howToBuyMstDatas,
            'raceNumDatas' => $raceNumDatas,
            'raceDate' => $raceDate,
            'votingRecordsIndexViewData' => $votingRecordsIndexViewData
        ];

        return view('voting_record.create', $params);
    }

    /** 特殊方式でのデータ作成（create） **/
    public function createSpecialMethod(Request $request)
    {
        // 投票する対象日を取得
        $raceDate = $request->query('race_date', date('Y-m-d'));

        // フォーム項目でレース場一覧とレース数の取得が必要な箇所があるので対応
        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst([
            'raceDate' => $raceDate
        ]);
        $howToBuyMstDatas = $this->howToBuyMstService->getAllHowToBuyMsts();
        $raceNumDatas = self::RACE_NUM_DATAS;

        // テンプレに渡す項目
        $votingRecordsIndexViewData = new VotingRecordsIndexView();
        $params = [
            'subTitle' => '新規作成',
            'raceSchedulesWithCourseDatas' => $raceSchedulesWithCourseDatas,
            'howToBuyMstDatas' => $howToBuyMstDatas,
            'raceNumDatas' => $raceNumDatas,
            'raceDate' => $raceDate,
            'votingRecordsIndexViewData' => $votingRecordsIndexViewData
        ];

        return view('voting_record.specialMethod.createSpecialMethod', $params);
    }

    /** 通常方式でのデータ複製（テンプレ呼び出し→データ作成は既存のstoreメソッドを利用） **/
    public function copy(string $id)
    {
        // 投票する対象日とコピー元の投票データを取得
        $votingRecordsIndexViewData = $this->votingRecordsIndexViewService->getVotingRecordsIndexViewData($id);
        $raceDate = $votingRecordsIndexViewData->getRaceDate()->format('Y-m-d');

        // 買い目だけリセット
        $votingRecordsIndexViewData->setVotingUmaBan("");

        // フォーム項目でレース場一覧とレース数の取得が必要な箇所があるので対応
        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst([
            'raceDate' => $raceDate
        ]);
        $howToBuyMstDatas = $this->howToBuyMstService->getAllHowToBuyMsts();
        $raceNumDatas = self::RACE_NUM_DATAS;

        // テンプレに渡す項目
        $params = [
            'subTitle' => '複製',
            'raceSchedulesWithCourseDatas' => $raceSchedulesWithCourseDatas,
            'howToBuyMstDatas' => $howToBuyMstDatas,
            'raceNumDatas' => $raceNumDatas,
            'raceDate' => $raceDate,
            'votingRecordsIndexViewData' => $votingRecordsIndexViewData
        ];

        return view('voting_record.create', $params);
    }

    /** 通常方式でのデータ作成（store） **/
    public function store(Request $request)
    {
        $request->validate([
            'voting_uma_ban' => 'required',
            'voting_amount' => 'required',
        ]);

        // 投票データ作成にrace_info_idが必要なので取得
        $raceInfo = $this->raceInfoService->getRaceInfoByUniqueColumn([
            'raceDate' => new DateTime($request->input('race_date')),
            'jyoCd' => $request->input('racecourse_mst'),
            'raceNum' => $request->input('race_num'),
        ]);

        $paramsForInsert = array(
            'race_info_id' => $raceInfo->getId(),
            'how_to_buy_mst_id' => $request->input('how_to_buy'),
            'voting_uma_ban' => $request->input('voting_uma_ban'),
            'voting_amount' => $request->input('voting_amount'),
            'refund_amount' => 0,
            'created_at' => new DateTime(date('Y-m-d H:i:s')),
            'updated_at' => new DateTime(date('Y-m-d H:i:s'))
        );

        $this->votingRecordService->createVotingRecord($paramsForInsert);

        return redirect()->route('voting_record.index')->with('success', '新しいデータの作成が完了しました');
    }

    /** 特殊方式でのデータ作成（store） **/
    public function storeSpecialMethod(Request $request)
    {
        // 投票データ作成にrace_info_idが必要なので取得
        $raceInfo = $this->raceInfoService->getRaceInfoByUniqueColumn([
            'raceDate' => new DateTime($request->input('race_date')),
            'jyoCd' => $request->input('racecourse_mst'),
            'raceNum' => $request->input('race_num'),
        ]);

        // 投票方式に応じてインサートするパラメータをフォーマットする
        $howToVote = $request->input('how_to_vote');
        $paramsForInsertList = [];

        if ($howToVote === 'nagashiTemplate') {
            // 「流し」用の処理を行う
            $formatParams = [
                'raceInfoId'      => $raceInfo->getId(),
                'howToBuyNagashi' => $request->input('how_to_buy_nagashi'), // 識別（単勝、複勝、etc）
                'howToNagashi'    => $request->input('how_to_nagashi'), // 流し方（軸1頭 or 軸2頭）
                'shaft'           => $request->input('shaft'), // 軸
                'partner'         => $request->input('partner'), // 相手
                'votingAmountNagashi' => $request->input('voting_amount_nagashi'), // 掛け金
            ];

            // まずParamのエラーチェック
            if ($this->votingRecordGeneral->checkNagashiData($formatParams)) {
                // 問題なければ整形作業をして、インサート用配列のリストを受け取る
                $paramsForInsertList = $this->votingRecordGeneral->formatNagashiData($formatParams);
            } else {
                // もしフォーマットエラーだった場合
                return redirect()->route('voting_record.createSpecialMethod')->with('erroe', '形式に問題があります');
            }
        }
        else if ($howToVote === 'boxTemplate') {
            // ボックス用の処理を行う
            $formatParams = [
                'raceInfoId'      => $raceInfo->getId(),
                'howToBuyBox'     => $request->input('how_to_buy_box'), // 識別（単勝、複勝、etc）
                'votingUmaBanBox' => $request->input('voting_uma_ban_box'), // 対象馬
                'votingAmountBox' => $request->input('voting_amount_box'), // 掛け金
            ];

            // まずParamのエラーチェック
            if ($this->votingRecordGeneral->checkBoxData($formatParams)) {
                // 問題なければ整形作業をして、インサート用配列のリストを受け取る
                $paramsForInsertList = $this->votingRecordGeneral->formatBoxData($formatParams);
            } else {
                // もしフォーマットエラーだった場合
                return redirect()->route('voting_record.createSpecialMethod')->with('erroe', '形式に問題があります');
            }
        }
        else if ($howToVote === 'formationTemplate') {
            // フォーメーション用の処理を行う
            $formatParams = [
                'raceInfoId'            => $raceInfo->getId(),
                'howToBuyFormation'     => $request->input('how_to_buy_formaation'), // 識別（単勝、複勝、etc）
                'votingUmaBan1'         => $request->input('voting_uma_ban_1_formaation'), /// フォーメーションの1着
                'votingUmaBan2'         => $request->input('voting_uma_ban_2_formaation'), /// フォーメーションの2着
                'votingUmaBan3'         => $request->input('voting_uma_ban_3_formaation'), /// フォーメーションの3着
                'votingAmountFormation' => $request->input('voting_amount_formaation'), // 掛け金
            ];

            // まずParamのエラーチェック
            if ($this->votingRecordGeneral->checkFormationData($formatParams)) {
                // 問題なければ整形作業をして、インサート用配列のリストを受け取る
                $paramsForInsertList = $this->votingRecordGeneral->formatFormationData($formatParams);
            } else {
                // もしフォーマットエラーだった場合
                return redirect()->route('voting_record.createSpecialMethod')->with('erroe', '形式に問題があります');
            }
        }
        else {
            return redirect()->route('voting_record.createSpecialMethod')->with('erroe', '投票方式を入力してください');
        }

        // 必要なレコード分、インサート作業を行う
        foreach ($paramsForInsertList as $paramsForInsert) {
            $paramsForInsert['race_info_id'] = $raceInfo->getId();
            $this->votingRecordService->createVotingRecord($paramsForInsert);
        }
        return redirect()->route('voting_record.index')->with('success', '新しいデータの作成が完了しました');
    }

    public function show(string $id)
    {
        // 一旦今回は使用しない
    }

    /* 既存データの修正（テンプレ呼び出し） */
    public function edit(string $id)
    {
        // 投票する対象日と編集元の投票データを取得
        $votingRecordsIndexViewData = $this->votingRecordsIndexViewService->getVotingRecordsIndexViewData($id);
        $raceDate = $votingRecordsIndexViewData->getRaceDate()->format('Y-m-d');

        // フォーム項目でレース場一覧とレース数の取得が必要な箇所があるので対応
        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst([
            'raceDate' => $raceDate
        ]);
        $howToBuyMstDatas = $this->howToBuyMstService->getAllHowToBuyMsts();
        $raceNumDatas = self::RACE_NUM_DATAS;

        // テンプレに渡す項目
        $params = [
            'subTitle' => '編集',
            'raceSchedulesWithCourseDatas' => $raceSchedulesWithCourseDatas,
            'howToBuyMstDatas' => $howToBuyMstDatas,
            'raceNumDatas' => $raceNumDatas,
            'raceDate' => $raceDate,
            'votingRecordsIndexViewData' => $votingRecordsIndexViewData
        ];

        return view('voting_record.edit', $params);
    }

    /* 既存データの修正（実行） */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'voting_uma_ban' => 'required',
            'voting_amount' => 'required',
        ]);

        $raceInfo = $this->raceInfoService->getRaceInfoByUniqueColumn([
            'raceDate' => new DateTime($request->input('race_date')),
            'jyoCd' => $request->input('racecourse_mst'),
            'raceNum' => $request->input('race_num'),
        ]);

        $paramsForUpdate = array(
            'race_info_id' => $raceInfo->getId(),
            'how_to_buy_mst_id' => $request->input('how_to_buy'),
            'voting_uma_ban' => $request->input('voting_uma_ban'),
            'voting_amount' => $request->input('voting_amount'),
            'refund_amount' => 0,
            'updated_at' => new DateTime(date('Y-m-d H:i:s'))
        );

        $this->votingRecordService->updateVotingRecord($id, $paramsForUpdate);

        return redirect()->route('voting_record.index')->with('success', 'データの修正が完了しました');
    }

    /** 投票データの削除 **/
    public function destroy(string $id)
    {
        $this->votingRecordService->deleteVotingRecord($id);

       return redirect()->route('voting_record.index')->with('success', 'データの削除が完了しました');
    }
}
