<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;


class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->user();

        // $user->token;
    }

    public function login(Request $request)
    {
        //ensure that fields are filled
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->except(['_token']);

        //find user information
        $user = User::where('email', $request->email)->first();

        if (auth()->attempt($credentials)) {
            return redirect('/');
        }
        else {
            session()->flash('message', 'Invalid credentials!');
            return redirect()->back();
        }
        // dd($user);
    }

    public function logout()
    {
        // dd("in logout");
        Auth::logout();
        return Redirect('/login');
    }



    public function showLoginForm()
    {
        return view('auth.login');
    }
}