<?php

namespace App\Http\Controllers;

use App\Services\ItemService;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    private $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = $this->itemService->getAllItems();

        return view('items.index', ['items' => $items]);
    }

   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
       return view('items.create');
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'count' => 'required|integer'
        ]);

        $this->itemService->createItem($validated);

       return redirect()->route('items.index')->with('success', 'Item created successfully.');
   }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = $this->itemService->getItem($id);

        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = $this->itemService->getItem($id);

        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'count' => 'required|integer'
        ]);

        $this->itemService->updateItem($id, $validated);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->itemService->deleteItem($id);

       return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
