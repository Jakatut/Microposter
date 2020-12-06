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
     * Displays a list of profiles that the user with the provided id (or logged in user if not provided)
     * is being followed by.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function followers(Request $request, $id = null) {
        // User profile requests (no id)
        if ($id === null) {
            $id = Auth::user()->id;
        }
        $user = User::find($id);
        $followers = Follower::where(['user_id' => $id])->get();
        $foundUsers = [];
        foreach($followers as $follower) {
            array_push($foundUsers, User::find($follower->follower_id));
        }

        return view('follows', [ 'user' => $user, 'foundUsers' => $foundUsers, 'followContext' => 'Followers' ]);
    }


    /**
     * Displays a list of profiles that the user with the provided id (or logged in user if not provided)
     * is following.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function following(Request $request, $id = null) {
        // User profile requests (no id)
        if ($id === null) {
            $user = Auth::user();
            $id = $user->id;
        } else {
            $user = User::find($id);
        }
        $following = Follower::where('follower_id', $id)->get();
        $foundUsers = [];
        foreach($following as $follower) {
            array_push($foundUsers, User::find($follower->user_id));
        }
        return view('follows', [ 'user' => $user, 'foundUsers' => $foundUsers, 'followContext' => 'Following' ]);
    }
}
