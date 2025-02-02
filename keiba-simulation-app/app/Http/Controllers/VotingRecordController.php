<?php

namespace App\Http\Controllers;

use App\Services\Crud\VotingRecordService;
use App\Services\Crud\VotingRecordDetailService;
use App\Services\Crud\BoxVotingRecordService;
use App\Services\Crud\FormationVotingRecordService;
use App\Services\Crud\NagashiVotingRecordService;
use App\Services\Crud\RaceScheduleService;
use App\Services\Crud\RaceInfoService;
use App\Services\Crud\HowToBuyMstService;
use App\Services\Crud\RacecourseMstService;
use App\Services\Crud\BettingTypeMstService;
use App\Services\General\VotingRecordGeneral;
use App\Services\General\AuthGeneral;
use Illuminate\Http\Request;

class VotingRecordController extends Controller
{
    private $votingRecordService;
    private $votingRecordDetailService;
    private $boxVotingRecordService;
    private $formationVotingRecordService;
    private $nagashiVotingRecordService;
    private $raceScheduleService;
    private $howToBuyMstService;
    private $raceInfoService;
    private $racecourseMstService;
    private $votingRecordGeneral;
    private $bettingTypeMstService;
    private $authGeneral;

    const DEFAULT_PAZE = 1;
    const RACE_NUM_DATAS = [1,2,3,4,5,6,7,8,9,10,11,12];

    public function __construct(
        VotingRecordService $votingRecordService,
        VotingRecordDetailService $votingRecordDetailService,
        BoxVotingRecordService $boxVotingRecordService,
        FormationVotingRecordService $formationVotingRecordService,
        NagashiVotingRecordService $nagashiVotingRecordService,
        RaceScheduleService $raceScheduleService,
        HowToBuyMstService $howToBuyMstService,
        RaceInfoService $raceInfoService,
        RacecourseMstService $racecourseMstService,
        BettingTypeMstService $bettingTypeMstService,
        VotingRecordGeneral $votingRecordGeneral,
        AuthGeneral $authGeneral
    ) {
        $this->votingRecordService = $votingRecordService;
        $this->votingRecordDetailService = $votingRecordDetailService;
        $this->boxVotingRecordService = $boxVotingRecordService;
        $this->formationVotingRecordService = $formationVotingRecordService;
        $this->nagashiVotingRecordService = $nagashiVotingRecordService;
        $this->raceScheduleService = $raceScheduleService;
        $this->howToBuyMstService = $howToBuyMstService;
        $this->raceInfoService = $raceInfoService;
        $this->racecourseMstService = $racecourseMstService;
        $this->bettingTypeMstService = $bettingTypeMstService;
        $this->votingRecordGeneral = $votingRecordGeneral;
        $this->authGeneral = $authGeneral;
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
            'racecourse' => $racecourse,
            'userId' => $this->authGeneral->getLoginUser()->getId()
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

    /** 投票詳細 **/
    public function show(string $id)
    {
        $votingRecord = $this->votingRecordService->getVotingRecord($id);
        $racecourseMst = $this->racecourseMstService->getRacecourseMstByUniqueColumn(['jyoCd' => $votingRecord->getRaceInfo()->getJyoCd()]);

        // テンプレに渡す項目
        $params = [
            'votingRecord' => $votingRecord,
            'racecourseMst' => $racecourseMst
        ];

        return view('voting_record.show', $params);
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
            'userId' => $this->authGeneral->getLoginUser()->getId()
        ];
        $totallingDatas = $this->votingRecordDetailService->getVotingRecordDetailByFromToDate($fromRaceDate, $toRaceDate, $otherWhereParams);

        # 取得したデータをもとに集計する
        $totalVotingAmount = 0;
        $totalRefundAmount = 0;
        foreach ($totallingDatas as $votingRecordDetail) {
            $totalVotingAmount += $votingRecordDetail->getVotingAmount();
            $totalRefundAmount += $votingRecordDetail->getRefundAmount();
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

    /** 投票データ作成（テンプレート呼び出し） **/
    public function create(Request $request)
    {
        // 投票する対象日を取得
        $raceDate = $request->query('race_date', date('Y-m-d'));

        // フォーム項目でレース場一覧とレース数の取得が必要な箇所があるので対応
        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst([
            'raceDate' => $raceDate
        ]);

        // テンプレに渡す項目
        $params = [
            'subTitle' => '新規作成',
            'raceSchedulesWithCourseDatas' => $raceSchedulesWithCourseDatas,
            'howToBuyMstDatas' => $this->howToBuyMstService->getAllHowToBuyMsts(),
            'bettingTypeMstDatas' => $this->bettingTypeMstService->getAllBettingTypeMsts(),
            'raceNumDatas' => self::RACE_NUM_DATAS,
            'raceDate' => $raceDate,
        ];

        return view('voting_record.create', $params);
    }

    /** 投票データ作成（実施） **/
    public function store(Request $request)
    {
        $raceDate = $request->input('race_date');
        $jyoCd = $request->input('racecourse_mst');
        $raceNum = $request->input('race_num');

        // 投票データ作成にrace_infoが必要なので取得
        $raceInfo = $this->raceInfoService->getRaceInfoByUniqueColumn([
            'raceDate' => new \DateTime($raceDate),
            'jyoCd' => $jyoCd,
            'raceNum' => $raceNum,
        ]);

        if (empty($raceInfo)) {
            return redirect()->route('voting_record.create')->with('error', 'レース情報が存在しません');
        }

        // 投票方式に応じてインサートするパラメータをフォーマットする
        $howToBuy = $request->input('how_to_buy');

        //先に買い方と馬券の種類のデータは取得しておく
        $howToBuyTemplateList = collect(config('config.HOW_TO_BUY_LIST'));
        $howToBuyId = $howToBuyTemplateList->search($howToBuy);
        $howToBuyMst = $this->howToBuyMstService->getHowToBuyMst($howToBuyId);
        $bettingTypeMst = $this->bettingTypeMstService->getBettingTypeMst($request->input('betting_type'));

        // 共通のパラムになるものは定義
        $createParams = [
            'raceInfo' => $raceInfo,
            'howToBuyMst' => $howToBuyMst,
            'bettingTypeMst' => $bettingTypeMst,
        ];

        // 買い方に応じて分岐して処理していく
        if ($howToBuy === 'Normal') {
            // 通常
            $createParams['votingUmaBan'] = $request->input('voting_uma_ban_normal'); // 買い目
            $createParams['votingAmount'] = $request->input('voting_amount_normal'); // 掛け金

            $resultCode = $this->execCreateNormalData($createParams);
        }
        else if ($howToBuy === 'Box') {
            // ボックス
            $createParams['votingUmaBanBox'] = $request->input('voting_uma_ban_box');// 買い目
            $createParams['votingAmountBox'] = $request->input('voting_amount_box'); // 掛け金

            $resultCode = $this->execCreateBoxData($createParams);
        }
        else if ($howToBuy === 'Nagashi') {
            // ながし
            $createParams['howToNagashi'] = $request->input('how_to_nagashi'); // ながし方
            $createParams['shaft'] = $request->input('shaft');// 軸
            $createParams['partner'] = $request->input('partner'); // 相手
            $createParams['votingAmountNagashi'] = $request->input('voting_amount_nagashi'); // 掛け金

            $resultCode = $this->execCreateNagashiData($createParams);
        }
        else if ($howToBuy === 'Formation') {
            // フォーメーション
            $createParams['votingUmaBan1'] = $request->input('voting_uma_ban_1_formaation'); // 1着買い目
            $createParams['votingUmaBan2'] = $request->input('voting_uma_ban_2_formaation'); // 2着買い目
            $createParams['votingUmaBan3'] = $request->input('voting_uma_ban_3_formaation'); // 3着買い目
            $createParams['votingAmountFormation'] = $request->input('voting_amount_formation'); // 掛け金

            $resultCode = $this->execCreateFormationData($createParams);
        }
        else {
            return redirect()->route('voting_record.create')->with('error', '投票方式を入力してください');
        }

        switch ($resultCode) {
            case '01':
                //return redirect()->route('voting_record.index')->with('success', '新しいデータの作成が完了しました');
                return redirect()->route('voting_record.index', [
                        'race_date' => $raceDate,
                        'racecourse' => $jyoCd
                    ])
                    ->with('success', '新しいデータの作成が完了しました');
                break;
            case '11':
                return redirect()->route('voting_record.create')->with('error', '買い目が正しくないです');
                break;
        }

        return redirect()->route('voting_record.index')->with('error', 'データ作成中に何かしらのエラーが発生しました');
    }

    /** 投票金額の更新（テンプレート表示） **/
    public function editVotingAmount(string $id) {
        $votingRecord = $this->votingRecordService->getVotingRecord($id);
        $racecourseMst = $this->racecourseMstService->getRacecourseMstByUniqueColumn(['jyoCd' => $votingRecord->getRaceInfo()->getJyoCd()]);

        // テンプレに渡す項目
        $params = [
            'votingRecord' => $votingRecord,
            'racecourseMst' => $racecourseMst
        ];

        return view('voting_record.updateVotingAmount', $params);
    }

    /** 投票金額の更新（実施） **/
    public function updateVotingAmount(string $id, Request $request) {
        $votingRecord = $this->votingRecordService->getVotingRecord($id);
        $racecourseMst = $this->racecourseMstService->getRacecourseMstByUniqueColumn(['jyoCd' => $votingRecord->getRaceInfo()->getJyoCd()]);

        // 更新対象の金額
        $updateVotingAmount = $request->input('update_voting_amount');

        $howToBuy = $votingRecord->getHowToBuyMst()->getHowToBuyCode();

        if ($howToBuy === "box") {
            // ボックス
            $boxVotingRecord = $this->boxVotingRecordService->getBoxVotingRecordByUniqueColumn([
                'votingRecord' => $votingRecord,
            ]);

            // box_voting_recordを更新
            $this->boxVotingRecordService->updateBoxVotingRecord($boxVotingRecord->getId(), [
                'votingRecord' => $votingRecord,
                'votingUmaBanBox' => $boxVotingRecord->getVotingUmaBanBox(),
                'votingAmountBox' => $updateVotingAmount,
            ]);
        }
        else if ($howToBuy === "nagashi") {
            // ながし
            $nagashiotingRecord = $this->nagashiVotingRecordService->getNagashiVotingRecordByUniqueColumn([
                'votingRecord' => $votingRecord,
            ]);

            // nagashi_voting_recordを更新
            $this->nagashiVotingRecordService->updateNagashiVotingRecord($nagashiotingRecord->getId(), [
                'votingRecord' => $votingRecord,
                'howToNagashi' => $nagashiotingRecord->getHowToNagashi(),
                'shaft' => $nagashiotingRecord->getShaft(),
                'partner' => $nagashiotingRecord->getPartner(9),
                'votingAmountBox' => $updateVotingAmount,
            ]);
        }
        else if ($howToBuy === "formation") {
            // フォーメーション
            $formationBoxVotingRecord = $this->formationVotingRecordService->getFormationVotingRecordByUniqueColumn([
                'votingRecord' => $votingRecord,
            ]);

            // formation_voting_recordを更新
            $this->formationVotingRecordService->updateFormationVotingRecord($formationBoxVotingRecord->getId(), [
                'votingRecord' => $votingRecord,
                'votingUmaBan1' => $formationBoxVotingRecord->getVotingUmaBan1(),
                'votingUmaBan2' => $formationBoxVotingRecord->getVotingUmaBan2(),
                'votingUmaBan3' => $formationBoxVotingRecord->getVotingUmaBan3(),
                'votingAmountFormation' => $updateVotingAmount,
            ]);
        }

        // voting_record_detailを更新
        foreach ($votingRecord->getVotingRecordDetails() as $detail) {
            $this->votingRecordDetailService->updateVotingRecordDetail($detail->getId(), [
                'votingRecord' => $votingRecord,
                'votingUmaBan' => $detail->getVotingUmaBan(),
                'votingAmount' =>  $updateVotingAmount,
            ]);
        }

        // テンプレに渡す項目
        $params = [
            'votingRecord' => $votingRecord,
            'racecourseMst' => $racecourseMst
        ];

        return view('voting_record.show', $params);
    }

    /** 投票データの削除 **/
    public function destroy(string $id)
    {
        $this->votingRecordService->deleteVotingRecord($id);

        return redirect()->route('voting_record.index')->with('success', 'データの削除が完了しました');
    }

    // 通常方式でのデータ作成
    private function execCreateNormalData($params) {
        $resultCode = '01'; //成功

        // 必要な変数を取得
        $raceInfo = $params['raceInfo'];
        $howToBuyMst = $params['howToBuyMst'];
        $bettingTypeMst = $params['bettingTypeMst'];
        $votingUmaBan = $params['votingUmaBan'];
        $votingAmount = $params['votingAmount'];

        // voting_record作成
        $votingRecord = $this->votingRecordService->createVotingRecord([
            'raceInfo'       => $raceInfo,
            'howToBuyMst'    => $howToBuyMst,
            'bettingTypeMst' => $bettingTypeMst,
        ]);

        $this->votingRecordDetailService->createVotingRecordDetail([
            'votingRecord' => $votingRecord,
            'votingUmaBan' => $votingUmaBan,
            'votingAmount' => $votingAmount,
        ]);

        return $resultCode;
    }

    // ボックス方式でのデータ作成
    private function execCreateBoxData($params) {
        $resultCode = '01'; //成功

        // 必要な変数を取得
        $raceInfo = $params['raceInfo'];
        $howToBuyMst = $params['howToBuyMst'];
        $bettingTypeMst = $params['bettingTypeMst'];
        $votingUmaBanBox = $params['votingUmaBanBox'];
        $votingAmountBox = $params['votingAmountBox'];

        $formatParams = [
            'bettingTypeMstId' => $bettingTypeMst->getId(), 
            'votingUmaBanBox'  => $votingUmaBanBox,
            'votingAmountBox'  => $votingAmountBox,
        ];

        // データのチェック
        $resultCode = $this->votingRecordGeneral->checkBoxData($formatParams);
        if ($resultCode !== '01') {
            return $resultCode;
        }

        // voting_record作成
        $votingRecord = $this->votingRecordService->createVotingRecord([
            'raceInfo'       => $raceInfo,
            'howToBuyMst'    => $howToBuyMst,
            'bettingTypeMst' => $bettingTypeMst,
        ]);

        // box_voting_record作成
        $boxVotingRecord = $this->boxVotingRecordService->createBoxVotingRecord([
            'votingRecord'    => $votingRecord,
            'votingUmaBanBox' => $votingUmaBanBox,
            'votingAmountBox' => $votingAmountBox,
        ]);

        // voting_record_detail作成
        $formatParams['votingRecord'] = $votingRecord;
        $paramsForInsertList = $this->votingRecordGeneral->formatBoxData($formatParams);
        foreach ($paramsForInsertList as $paramsForInsert) {
            $this->votingRecordDetailService->createVotingRecordDetail($paramsForInsert);
        }

        return $resultCode;
    }

    // ながし方式でのデータ作成
    private function execCreateNagashiData($params) {
        // 必要な変数を取得
        $raceInfo = $params['raceInfo'];
        $howToBuyMst = $params['howToBuyMst'];
        $bettingTypeMst = $params['bettingTypeMst'];
        $howToNagashi = $params['howToNagashi'];
        $shaft = $params['shaft'];
        $partner = $params['partner'];
        $votingAmountNagashi = $params['votingAmountNagashi'];

        $formatParams = [
            'bettingTypeMstId'    => $bettingTypeMst->getId(), 
            'howToNagashi'        => $howToNagashi,
            'shaft'               => $shaft,
            'partner'             => $partner,
            'votingAmountNagashi' => $votingAmountNagashi,
        ];

        // データのチェック
        $resultCode = $this->votingRecordGeneral->checkNagashiData($formatParams);
        if ($resultCode !== '01') {
            return $resultCode;
        }

        // voting_record作成
        $votingRecord = $this->votingRecordService->createVotingRecord([
            'raceInfo'       => $raceInfo,
            'howToBuyMst'    => $howToBuyMst,
            'bettingTypeMst' => $bettingTypeMst,
        ]);

        // nagashi_voting_record作成
        $nagashiVotingRecord = $this->nagashiVotingRecordService->createNagashiVotingRecord([
            'votingRecord'        => $votingRecord,
            'howToNagashi'        => $howToNagashi,
            'shaft'               => $shaft,
            'partner'             => $partner,
            'votingAmountNagashi' => $votingAmountNagashi,
        ]);

        // voting_record_detail作成
        $formatParams['votingRecord'] = $votingRecord;
        $paramsForInsertList = $this->votingRecordGeneral->formatNagashiData($formatParams);
        foreach ($paramsForInsertList as $paramsForInsert) {
            $this->votingRecordDetailService->createVotingRecordDetail($paramsForInsert);
        }

        return $resultCode;

    }

    // フォーメーション方式でのデータ作成
    private function execCreateFormationData($params) {
        // 必要な変数を取得
        $raceInfo = $params['raceInfo'];
        $howToBuyMst = $params['howToBuyMst'];
        $bettingTypeMst = $params['bettingTypeMst'];
        $votingUmaBan1 = $params['votingUmaBan1'];
        $votingUmaBan2 = $params['votingUmaBan2'];
        $votingUmaBan3 = $params['votingUmaBan3'];
        $votingAmountFormation = $params['votingAmountFormation'];

        $formatParams = [
            'bettingTypeMstId' => $bettingTypeMst->getId(), 
            'votingUmaBan1' => $votingUmaBan1,
            'votingUmaBan2' => $votingUmaBan2,
            'votingUmaBan3' => $votingUmaBan3,
            'votingAmountFormation' =>  $votingAmountFormation,
        ];

        // データのチェック
        $resultCode = $this->votingRecordGeneral->checkFormationData($formatParams);
        if ($resultCode !== '01') {
            return $resultCode;
        }

        // voting_record作成
        $votingRecord = $this->votingRecordService->createVotingRecord([
            'raceInfo'       => $raceInfo,
            'howToBuyMst'    => $howToBuyMst,
            'bettingTypeMst' => $bettingTypeMst,
        ]);

        // formation_voting_record作成
        $formationVotingRecord = $this->formationVotingRecordService->createFormationVotingRecord([
            'votingRecord'          => $votingRecord,
            'votingUmaBan1'         => $votingUmaBan1,
            'votingUmaBan2'         => $votingUmaBan2,
            'votingUmaBan3'         => $votingUmaBan3,
            'votingAmountFormation' => $votingAmountFormation,
        ]);

        // voting_record_detail作成
        $formatParams['votingRecord'] = $votingRecord;
        $paramsForInsertList = $this->votingRecordGeneral->formatFormationData($formatParams);
        foreach ($paramsForInsertList as $paramsForInsert) {
            $this->votingRecordDetailService->createVotingRecordDetail($paramsForInsert);
        }

        return $resultCode;
    }
}
