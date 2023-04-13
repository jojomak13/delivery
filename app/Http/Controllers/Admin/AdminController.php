<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::query()->latest()->paginate(10);

        return Inertia::render('Admin/Admins/Index', [
            'admins' => $admins,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Admins/Create'); 
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|max:255|confirmed',
        ]);

        Admin::create([
            'password' => Bcrypt($request->input('password'))
        ] + $data);

        return redirect()->route('admin.admins.index');
    }

    public function edit(Admin $admin)
    {
        return Inertia::render('Admin/Admins/Edit', [
            'admin' => $admin,
        ]); 
    }

    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|max:255|confirmed',
        ]);

        unset($data['password']);

        if($request->input('password')){
            $data['password'] = Bcrypt($request->input('password'));
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index');
    }
    
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect()->route('admin.admins.index');
    }

}
