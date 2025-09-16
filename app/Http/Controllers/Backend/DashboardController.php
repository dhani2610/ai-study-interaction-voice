<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('admin')->check() == null) {
                return redirect()->route('admin.login');
            }
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }


    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('dashboard.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view dashboard !');
        }

        $data['page_title'] = 'Dashboard';

        return view('backend.pages.dashboard.index', $data);
    }
}
