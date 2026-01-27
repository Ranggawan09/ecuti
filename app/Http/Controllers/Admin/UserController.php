<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('employee')->get();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'nip'   => 'required|unique:users',
            'role'  => 'required',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'nip'      => $request->nip,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'User berhasil dibuat');
    }
}
