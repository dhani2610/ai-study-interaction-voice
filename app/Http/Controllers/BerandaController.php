<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class BerandaController extends Controller
{


    public function register()
    {
        $data['page_title'] = 'Register';
        $data['roles']  = Role::where('name','!=','superadmin')->get();
        return view('backend.auth.register', $data);
    }

    public function registerStore(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:50',
                'email' => 'required|max:100|email|unique:admins',
                'username' => 'required|max:100|unique:admins',
                'password' => 'required|min:6',
            ]);

            // Create New Admin
            $admin = new Admin();
            $admin->name = $request->name;
            $admin->username = $request->username;
            $admin->email = $request->email;
            $admin->status = 0;
            $admin->password = Hash::make($request->password);
            $admin->save();

            $admin->assignRole($request->roles);

            session()->flash('success', 'Register berhasil. Akun Anda sedang menunggu validasi/approval oleh admin sebelum dapat mengakses panel.');
            return redirect('admin/login');
        } catch (\Throwable $th) {
            session()->flash('failed', $th->getMessage());
            return redirect('admin/register');
        }
    }
}
