<?php

namespace App\Http\Controllers;

use App\Entities\User;
use App\Services\Crud\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();

        return view('user.index', ['users' => $users]);
    }

   public function create()
   {
       $user = new User();

       return view('user.create', compact('user'));
   }

   public function store(Request $request)
   {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:12',
            'password_confirm' => 'required|string|max:12',
        ]);

        if ($request->input('password') !== $request->input('password_confirm')) {
            return redirect()->back()->with('error', 'パスワードが一致しません');
        }

        $this->userService->createUser($validated);

        return redirect()->route('user.index')->with('success', '新しいデータの作成が完了しました');
   }

    public function edit(string $id)
    {
        $user = $this->userService->getUser($id);

        return view('user.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:12',
        ]);

        $this->userService->updateUser($id, $validated);

        return redirect()->route('user.index')->with('success', 'データの修正が完了しました');
    }

    public function destroy(string $id)
    {
        $this->userService->deleteUser($id);

       return redirect()->route('user.index')->with('success', 'データの削除が完了しました');
    }
}
