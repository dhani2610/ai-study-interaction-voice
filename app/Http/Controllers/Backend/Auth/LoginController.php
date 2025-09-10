<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ADMIN_DASHBOARD;

    /**
     * show login form for admin guard
     *
     * @return void
     */
    public function showLoginForm()
    {
        return view('backend.auth.login');
    }


    /**
     * login admin
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        // Validate Login Data
        $request->validate([
            'email' => 'required|max:50',
            'password' => 'required',
        ]);

        $credentials = [
            ['email' => $request->email, 'password' => $request->password],
            ['username' => $request->email, 'password' => $request->password]
        ];

        foreach ($credentials as $credential) {
            if (Auth::guard('admin')->attempt($credential, $request->remember)) {
                $user = Auth::guard('admin')->user();

                // Cek status akun
                if ($user->status == 0) {
                    Auth::guard('admin')->logout();
                    session()->flash('failed', 'Akun Anda sedang dalam proses approval oleh admin.');
                    return back();
                } elseif ($user->status == 2) {
                    Auth::guard('admin')->logout();
                    session()->flash('error', 'Akun Anda telah dinonaktifkan oleh admin.');
                    return back();
                } elseif ($user->status == 1) {
                    $userRole = $user->getRoleNames()->first(); // Get the first role name
                    session()->flash('success', 'Successfully Logged in!');
                    return redirect()->route('admin.dashboard');
                }
            }
        }

        // Jika login gagal
        session()->flash('error', 'Invalid email/username or password');
        return back();
    }

    /**
     * logout admin guard
     *
     * @return void
     */
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
