<?php

namespace App\Http\Controllers;

use App\Entities\VotingRecordsIndexView;
use App\Services\Crud\VotingRecordService;
use App\Services\Crud\RaceScheduleService;
use App\Services\Crud\RaceInfoService;
use App\Services\Crud\HowToBuyMstService;
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

    const DEFAULT_PAZE = 1;
    const RACE_NUM_DATAS = [1,2,3,4,5,6,7,8,9,10,11,12];

    public function __construct()
    {
        $this->votingRecordService = app(VotingRecordService::class);
        $this->votingRecordsIndexViewService = app(VotingRecordsIndexViewService::class);
        $this->raceScheduleService = app(RaceScheduleService::class);
        $this->howToBuyMstService = app(HowToBuyMstService::class);
        $this->raceInfoService = app(RaceInfoService::class);
    }

    public function index(Request $request)
    {
        $page = $request->query('page', self::DEFAULT_PAZE);
        $pageSize = $request->query('page_size', config('config.INDEX_PAGE_SIZE'));

        // 検索
        $raceDate = $request->query('race_date', date('Y-m-d'));
        $raceNum =$request->query('racecourse_mst', NULL);
        $racecourse = $request->query('race_num', NULL);

        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst([
            'raceDate' => $raceDate
        ]);

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

   public function create(Request $request)
   {
        $raceDate = $request->query('race_date', date('Y-m-d'));

        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst([
            'raceDate' => $raceDate
        ]);
        $howToBuyMstDatas = $this->howToBuyMstService->getAllHowToBuyMsts();
        $raceNumDatas = self::RACE_NUM_DATAS;

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

   public function copy(string $id)
    {
        $votingRecordsIndexViewData = $this->votingRecordsIndexViewService->getVotingRecordsIndexViewData($id);

        $raceDate = $votingRecordsIndexViewData->getRaceDate()->format('Y-m-d');

        // 買い目だけリセット
        $votingRecordsIndexViewData->setVotingUmaBan("");

        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst([
            'raceDate' => $raceDate
        ]);
        $howToBuyMstDatas = $this->howToBuyMstService->getAllHowToBuyMsts();
        $raceNumDatas = self::RACE_NUM_DATAS;

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

   public function store(Request $request)
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

    public function show(string $id)
    {
        $votingRecordsIndexViewData = $this->votingRecordsIndexViewService->getVotingRecordsIndexViewData($id);

        return view('voting_record.show', compact('votingRecordsIndexViewData'));
    }

    public function edit(string $id)
    {
        $votingRecordsIndexViewData = $this->votingRecordsIndexViewService->getVotingRecordsIndexViewData($id);

        $raceDate = $votingRecordsIndexViewData->getRaceDate()->format('Y-m-d');

        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst([
            'raceDate' => $raceDate
        ]);

        $howToBuyMstDatas = $this->howToBuyMstService->getAllHowToBuyMsts();
        $raceNumDatas = self::RACE_NUM_DATAS;

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

    public function destroy(string $id)
    {
        $this->votingRecordService->deleteVotingRecord($id);

       return redirect()->route('voting_record.index')->with('success', 'データの削除が完了しました');
    }
}
