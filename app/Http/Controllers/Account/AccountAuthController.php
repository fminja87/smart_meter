<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AccountAuthController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function accontLogin(){

        return view('accountant.login');
    }

    /**
     * @throws ValidationException
     */
    public function accountAuth(Request $request): \Illuminate\Http\RedirectResponse
    {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credentials = ['email' => $request->email, 'password' => $request->password];
        $remember = $request->remember;

        $this->validate($request, ['email' => 'required|email', 'password' => 'required|min:6']);
        if (Auth::guard('accountant')->attempt($credentials, $remember)) {

            return redirect()->intended(route('accountant.home'));

        }


        return redirect()->back()->with('error','This credentials do not match to our records')->withInput($request->only('email '));
    }

    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::guard('accountant')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->intended(route('accountant.login'));
    }
}
