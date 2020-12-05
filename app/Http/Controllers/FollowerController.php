<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FollowerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Retrieve a list of followers for the provided id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function followers(Request $request, $id) {
        $user = User::find($id);
        $followers = [];
        if ($user) {
            $followers = $user->followers()->get();
        }
        return view('follows', [ 'user' => $user, 'followers' => $followers, 'followContext' => 'followers' ]);
    }


    /**
     * Display a profile with the given id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function following(Request $request, $id) {
        $user = Auth::user();
        $following = Follower::where('follower_id', $id)->get();
        return view('follows', [ 'user' => $user, 'following' => $following, 'followContext' => 'following' ]);
    }
}
