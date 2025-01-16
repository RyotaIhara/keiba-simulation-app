<?php

namespace App\Http\Controllers;

use App\Services\Crud\VotingRecordService;
use Illuminate\Http\Request;

class VotingRecordController extends Controller
{
    private $votingRecordService;

    public function __construct(VotingRecordService $votingRecordService)
    {
        $this->votingRecordService = $votingRecordService;
    }

    public function index()
    {
        $votingRecords = $this->votingRecordService->getAllVotingRecords();

        return view('voting_record.index', ['votingRecords' => $votingRecords]);
    }

   public function create()
   {
       return view('voting_record.create');
   }

   public function store(Request $request)
   {
        $validated = $request->validate([
            'race_info_id ' => 'required',
            'how_to_buy_mst_id' => 'required',
            'voting_uma_ban' => 'required',
            'voting_amount' => 'required',
        ]);

        $this->votingRecordService->createVotingRecord($validated);

        return redirect()->route('voting_record.index')->with('success', '新しいデータの作成が完了しました');
   }

    public function show(string $id)
    {
        $votingRecord = $this->votingRecordService->getVotingRecord($id);

        return view('voting_record.show', compact('votingRecord'));
    }

    public function edit(string $id)
    {
        $votingRecord = $this->votingRecordService->getVotingRecord($id);

        return view('voting_record.edit', compact('votingRecord'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'race_info_id ' => 'required',
            'how_to_buy_mst_id' => 'required',
            'voting_uma_ban' => 'required',
            'voting_amount' => 'required',
        ]);

        $this->votingRecordService->updateVotingRecord($id, $validated);

        return redirect()->route('voting_record.index')->with('success', 'データの修正が完了しました');
    }

    public function destroy(string $id)
    {
        $this->votingRecordService->deleteVotingRecord($id);

       return redirect()->route('voting_record.index')->with('success', 'データの削除が完了しました');
    }
}
