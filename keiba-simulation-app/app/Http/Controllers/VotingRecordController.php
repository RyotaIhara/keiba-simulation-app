<?php

namespace App\Http\Controllers;

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

    public function __construct()
    {
        $this->votingRecordService = app(VotingRecordService::class);
        $this->votingRecordsIndexViewService = app(VotingRecordsIndexViewService::class);
        $this->raceScheduleService = app(RaceScheduleService::class);
        $this->howToBuyMstService = app(HowToBuyMstService::class);
        $this->raceInfoService = app(RaceInfoService::class);
    }

    public function index()
    {
        $votingRecordsIndexViewDatas = $this->votingRecordsIndexViewService->getAllVotingRecordsIndexViewDatas();

        return view('voting_record.index', ['votingRecordsIndexViewDatas' => $votingRecordsIndexViewDatas]);
    }

   public function create()
   {
        $raceDate = '2025-01-15';
        $whereParams = array(
            'race_date' => $raceDate
        );

        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst($whereParams);
        $howToBuyMstDatas = $this->howToBuyMstService->getAllHowToBuyMsts();
        $raceNumDatas = [1,2,3,4,5,6,7,8,9,10,11,12];

        $params = [
            'raceSchedulesWithCourseDatas' => $raceSchedulesWithCourseDatas,
            'howToBuyMstDatas' => $howToBuyMstDatas,
            'raceNumDatas' => $raceNumDatas,
            'raceDate' => $raceDate
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
        $whereParams = array(
            'race_date' => $raceDate
        );

        $raceSchedulesWithCourseDatas = $this->raceScheduleService->getRaceSchedulesWithCourseMst($whereParams);
        $howToBuyMstDatas = $this->howToBuyMstService->getAllHowToBuyMsts();
        $raceNumDatas = [1,2,3,4,5,6,7,8,9,10,11,12];

        $params = [
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
