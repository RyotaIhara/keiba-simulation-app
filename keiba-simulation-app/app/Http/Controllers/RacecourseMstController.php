<?php

namespace App\Http\Controllers;

use App\Services\RacecourseMstService;
use Illuminate\Http\Request;

class RacecourseMstController extends Controller
{
    private $racecourseMstService;

    public function __construct(RacecourseMstService $racecourseMstService)
    {
        $this->racecourseMstService = $racecourseMstService;
    }

    public function index()
    {
        $racecourseMsts = $this->racecourseMstService->getAllRacecourseMsts();

        return view('racecourse_mst.index', ['racecourseMsts' => $racecourseMsts]);
    }

   public function create()
   {
       return view('racecourse_mst.create');
   }

   public function store(Request $request)
   {
        $validated = $request->validate([
            'jyo_cd' => 'required',
            'racecourse_name' => 'required|string|max:255',
        ]);

        $this->racecourseMstService->createRacecourseMst($validated);

        return redirect()->route('racecourse_mst.index')->with('success', '新しいデータの作成が完了しました');
   }

    public function show(string $id)
    {
        $racecourseMst = $this->racecourseMstService->getRacecourseMst($id);

        return view('racecourse_mst.show', compact('racecourseMst'));
    }

    public function edit(string $id)
    {
        $racecourseMst = $this->racecourseMstService->getRacecourseMst($id);

        return view('racecourse_mst.edit', compact('racecourseMst'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'jyo_cd' => 'required',
            'racecourse_name' => 'required|string|max:255',
        ]);

        $this->racecourseMstService->updateRacecourseMst($id, $validated);

        return redirect()->route('racecourse_mst.index')->with('success', 'データの修正が完了しました');
    }

    public function destroy(string $id)
    {
        $this->racecourseMstService->deleteRacecourseMst($id);

       return redirect()->route('racecourse_mst.index')->with('success', 'データの削除が完了しました');
    }
}
