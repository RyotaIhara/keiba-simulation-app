<?php

namespace App\Http\Controllers;

use Doctrine\ORM\EntityManagerInterface;

use App\Entities\VotingRecord;
use App\Services\Crud\VotingRecordService;
use App\Services\Crud\BoxVotingRecordService;
use App\Services\Crud\FormationVotingRecordService;
use App\Services\Crud\NagashiVotingRecordService;
use App\Services\Crud\RaceScheduleService;
use App\Services\Crud\RaceInfoService;
use App\Services\Crud\HowToBuyMstService;
use App\Services\Crud\RacecourseMstService;
use App\Services\General\VotingRecordGeneral;
use Illuminate\Http\Request;
use DateTime;

class VotingRecordController extends Controller
{
    private $votingRecordService;
    private $boxVotingRecordService;
    private $formationVotingRecordService;
    private $nagashiVotingRecordService;
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
        $this->boxVotingRecordService = app(BoxVotingRecordService::class);
        $this->formationVotingRecordService = app(FormationVotingRecordService::class);
        $this->nagashiVotingRecordService = app(NagashiVotingRecordService::class);
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
        $raceNum =$request->query('race_num', NULL);
        $racecourse = $request->query('racecourse_mst', NULL);

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
        $tmpResult = $this->votingRecordService->getVotingRecordsIndexViewDatas($page, $pageSize, $searchForm);

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
        $params = [
            'subTitle' => '新規作成',
            'raceSchedulesWithCourseDatas' => $raceSchedulesWithCourseDatas,
            'howToBuyMstDatas' => $howToBuyMstDatas,
            'raceNumDatas' => $raceNumDatas,
            'raceDate' => $raceDate,
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
        $params = [
            'subTitle' => '新規作成',
            'raceSchedulesWithCourseDatas' => $raceSchedulesWithCourseDatas,
            'howToBuyMstDatas' => $howToBuyMstDatas,
            'raceNumDatas' => $raceNumDatas,
            'raceDate' => $raceDate,
        ];

        return view('voting_record.specialMethod.createSpecialMethod', $params);
    }

    /** 通常方式でのデータ複製（テンプレ呼び出し→データ作成は既存のstoreメソッドを利用） **/
    public function copy(string $id)
    {
        // 投票する対象日とコピー元の投票データを取得
        $votingRecord = $this->votingRecordService->getVotingRecord($id);
        $raceDate = $votingRecord->getRaceInfo()->getRaceDate()->format('Y-m-d');

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
            'votingRecord' => $votingRecord
        ];

        return view('voting_record.create', $params);
    }

    /* 既存データの修正（テンプレ呼び出し） */
    public function edit(string $id)
    {
        // 投票する対象日と編集元の投票データを取得
        $votingRecord = $this->votingRecordService->getVotingRecord($id);
        $raceDate = $votingRecord->getRaceInfo()->getRaceDate()->format('Y-m-d');

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
            'votingRecord' => $votingRecord
        ];

        return view('voting_record.edit', $params);
    }


    /** 通常方式でのデータ作成（store） **/
    public function store(Request $request, EntityManagerInterface $entityManager)
    {
        $howToBuyMstService = app(HowToBuyMstService::class);

        $entityManager->beginTransaction(); // トランザクション開始

        $request->validate([
            'voting_uma_ban' => 'required',
            'voting_amount' => 'required',
        ]);

        // 投票データ作成にrace_infoが必要なので取得
        $raceInfo = $this->raceInfoService->getRaceInfoByUniqueColumn([
            'raceDate' => new DateTime($request->input('race_date')),
            'jyoCd' => $request->input('racecourse_mst'),
            'raceNum' => $request->input('race_num'),
        ]);

        if (empty($raceInfo)) {
            return redirect()->route('voting_record.create')->with('error', 'レース情報が存在しません');
        }

        $howToBuyMst = $howToBuyMstService->getHowToBuyMst($request->input('how_to_buy'));

        $paramsForInsert = array(
            'raceInfo'     => $raceInfo,
            'howToBuyMst'  => $howToBuyMst,
            'votingUmaBan' => $request->input('voting_uma_ban'),
            'votingAmount' => $request->input('voting_amount'),
            'refundAmount' => 0,
            'createdAt'    => new DateTime(date('Y-m-d H:i:s')),
            'updatedAt'    => new DateTime(date('Y-m-d H:i:s'))
        );

        $this->votingRecordService->createVotingRecord($paramsForInsert);

        $entityManager->commit(); // すべて成功したらコミット
        return redirect()->route('voting_record.index')->with('success', '新しいデータの作成が完了しました');
    }

    /** 特殊方式でのデータ作成（store） **/
    public function storeSpecialMethod(Request $request, EntityManagerInterface $entityManager)
    {
        $howToBuyMstService = app(HowToBuyMstService::class);

        $entityManager->beginTransaction(); // トランザクション開始

        // 投票データ作成にrace_info_が必要なので取得
        $raceInfo = $this->raceInfoService->getRaceInfoByUniqueColumn([
            'raceDate' => new DateTime($request->input('race_date')),
            'jyoCd' => $request->input('racecourse_mst'),
            'raceNum' => $request->input('race_num'),
        ]);

        if (empty($raceInfo)) {
            return redirect()->route('voting_record.createSpecialMethod')->with('error', 'レース情報が存在しません');
        }

        // 投票方式に応じてインサートするパラメータをフォーマットする
        $howToVote = $request->input('how_to_vote');
        $paramsForInsertList = [];
        $howToBuyMst = $howToBuyMstService->getHowToBuyMst($request->input('how_to_buy')); // 識別（単勝、複勝、etc）

        if ($howToVote === 'nagashiTemplate') {
            // 必要な変数を取得
            $howToNagashi = $request->input('how_to_nagashi'); // 識別（単勝、複勝、etc）
            $shaft = $request->input('shaft'); // 軸
            $partner = $request->input('partner'); // 相手
            $votingAmountNagashi = $request->input('voting_amount_nagashi'); // 掛け金
            

            // 先にnagashi_voting_recordにはデータは作っておく
            $nagashiVotingRecord = $this->nagashiVotingRecordService->createNagashiVotingRecord([
                'raceInfo'            => $raceInfo,
                'howToBuyMst'         => $howToBuyMst,
                'howToNagashi'        => $howToNagashi,
                'shaft'               => $shaft,
                'partner'             => $partner,
                'votingAmountNagashi' => $votingAmountNagashi,
                'createdAt'           => new DateTime(date('Y-m-d H:i:s')),
                'updatedAt'           => new DateTime(date('Y-m-d H:i:s')),
            ]);

            // 「流し」用のフォーマット処理を行う
            $formatParams = [
                'nagashiVotingRecord' => $nagashiVotingRecord,
                'howToBuyMstId'       => $howToBuyMst->getId(), 
                'howToNagashi'        => $howToNagashi,
                'shaft'               => $shaft,
                'partner'             => $partner,
                'votingAmountNagashi' => $votingAmountNagashi,
            ];

            if ($this->votingRecordGeneral->checkNagashiData($formatParams)) {
                // インサート用配列のリストを受け取る
                $paramsForInsertList = $this->votingRecordGeneral->formatNagashiData($formatParams);
            } else {
                return redirect()->route('voting_record.createSpecialMethod')->with('error', '形式に問題があります');
            }
        }
        else if ($howToVote === 'boxTemplate') {
            // 必要な変数を取得
            $votingUmaBanBox = $request->input('voting_uma_ban_box'); // 対象馬
            $votingAmountBox = $request->input('voting_amount_box'); // 掛け金

            // 先にbox_voting_recordにはデータは作っておく
            $boxVotingRecord = $this->boxVotingRecordService->createBoxVotingRecord([
                'raceInfo'        => $raceInfo,
                'howToBuyMst'     => $howToBuyMst,
                'votingUmaBanBox' => $votingUmaBanBox,
                'votingAmountBox' => $votingAmountBox,
                'createdAt'       => new DateTime(date('Y-m-d H:i:s')),
                'updatedAt'       => new DateTime(date('Y-m-d H:i:s')),
            ]);

            // ボックス用のフォーマット処理を行う
            $formatParams = [
                'boxVotingRecord' => $boxVotingRecord,
                'howToBuyMstId'   => $howToBuyMst->getId(), 
                'votingUmaBanBox' => $votingUmaBanBox,
                'votingAmountBox' => $votingAmountBox,
            ];

            if ($this->votingRecordGeneral->checkBoxData($formatParams)) {
                // インサート用配列のリストを受け取る
                $paramsForInsertList = $this->votingRecordGeneral->formatBoxData($formatParams);
            } else {
                return redirect()->route('voting_record.createSpecialMethod')->with('error', '形式に問題があります');
            }

        }
        else if ($howToVote === 'formationTemplate') {
            // 必要な変数を取得
            $votingUmaBan1 = $request->input('voting_uma_ban_1_formaation'); // フォーメーションの1着
            $votingUmaBan2 = $request->input('voting_uma_ban_2_formaation'); // フォーメーションの2着
            $votingUmaBan3 = $request->input('voting_uma_ban_3_formaation'); // フォーメーションの3着
            $votingAmountFormation = $request->input('voting_amount_formaation'); // 掛け金

            // 先にformation_voting_recordにはデータは作っておく
            $formationVotingRecord =  $this->formationVotingRecordService->createFormationVotingRecord([
                'raceInfo'      => $raceInfo,
                'howToBuyMst'   => $howToBuyMst,
                'votingUmaBan1' => $votingUmaBan1,
                'votingUmaBan2' => $votingUmaBan2,
                'votingUmaBan3' => $votingUmaBan3,
                'votingAmountFormation' =>  $votingAmountFormation,
                'createdAt'     => new DateTime(date('Y-m-d H:i:s')),
                'updatedAt'     => new DateTime(date('Y-m-d H:i:s')),
            ]);

            // フォーメーション用のフォーマット処理を行う
            $formatParams = [
                'formationVotingRecord' => $formationVotingRecord,
                'howToBuyMstId'         => $howToBuyMst->getId(),
                'votingUmaBan1' => $votingUmaBan1,
                'votingUmaBan2' => $votingUmaBan2,
                'votingUmaBan3' => $votingUmaBan3,
                'votingAmountFormation' =>  $votingAmountFormation,
            ];

            if ($this->votingRecordGeneral->checkFormationData($formatParams)) {
                // インサート用配列のリストを受け取る
                $paramsForInsertList = $this->votingRecordGeneral->formatFormationData($formatParams);
            } else {
                return redirect()->route('voting_record.createSpecialMethod')->with('error', '形式に問題があります');
            }
        }
        else {
            return redirect()->route('voting_record.createSpecialMethod')->with('error', '投票方式を入力してください');
        }

        // voting_recordインサート作業を行う
        foreach ($paramsForInsertList as $paramsForInsert) {
            $paramsForInsert['raceInfo'] = $raceInfo;
            $paramsForInsert['howToBuyMst'] = $howToBuyMst;
            $paramsForInsert['createdAt'] = new DateTime(date('Y-m-d H:i:s'));
            $paramsForInsert['updatedAt'] = new DateTime(date('Y-m-d H:i:s'));

            $this->votingRecordService->createVotingRecord($paramsForInsert);
        }

        $entityManager->commit(); // すべて成功したらコミット
        return redirect()->route('voting_record.index')->with('success', '新しいデータの作成が完了しました');
    }

    public function show(string $id)
    {
        // 一旦今回は使用しない
    }

    /* 既存データの修正（実行） */
    public function update(Request $request, string $id, EntityManagerInterface $entityManager)
    {
        $howToBuyMstService = app(HowToBuyMstService::class);

        $entityManager->beginTransaction(); // トランザクション開始

        $request->validate([
            'voting_uma_ban' => 'required',
            'voting_amount' => 'required',
        ]);

        $raceInfo = $this->raceInfoService->getRaceInfoByUniqueColumn([
            'raceDate' => new DateTime($request->input('race_date')),
            'jyoCd' => $request->input('racecourse_mst'),
            'raceNum' => $request->input('race_num'),
        ]);

        if (empty($raceInfo)) {
            return redirect()->route('voting_record.create')->with('error', 'レース情報が存在しません');
        }

        $howToBuyMst = $howToBuyMstService->getHowToBuyMst($request->input('how_to_buy'));

        $paramsForUpdate = array(
            'race_info'      => $raceInfo,
            'howToBuyMst'    => $howToBuyMst,
            'voting_uma_ban' => $request->input('voting_uma_ban'),
            'voting_amount'  => $request->input('voting_amount'),
            'refund_amount'  => 0,
            'updated_at' => new DateTime(date('Y-m-d H:i:s'))
        );

        $this->votingRecordService->updateVotingRecord($id, $paramsForUpdate);

        $entityManager->commit(); // すべて成功したらコミット
        return redirect()->route('voting_record.index')->with('success', 'データの修正が完了しました');
    }

    /** 投票データの削除 **/
    public function destroy(string $id)
    {
        $this->votingRecordService->deleteVotingRecord($id);

       return redirect()->route('voting_record.index')->with('success', 'データの削除が完了しました');
    }
}
