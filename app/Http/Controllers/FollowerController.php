<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follower;
use App\Traits\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FollowerController extends Controller
{
    use UserImage;

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
            $id = auth()->id();
        }
        $user = User::find($id);
        $followers = Follower::where(['user_id' => $id])->get();
        $foundUsers = [];
        foreach($followers as $follower) {
            $followingUser = User::find($follower->follower_id);
            $following = Follower::isFollowing($follower->follower_id)['following'];
            $profileImageURL = $this->getProfileImageURL($followingUser);
            array_push( $foundUsers, ['details' => $followingUser, 'following' => $following, 'profileImageURL' => $profileImageURL]);
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
            $followerUser = User::find($follower->user_id);
            $following = Follower::isFollowing($follower->user_id)['following'];
            $profileImageURL = $this->getProfileImageURL($followerUser);
            array_push($foundUsers, ['details' => $followerUser, 'following' => $following, 'profileImageURL' => $profileImageURL]);
        }
        return view('follows', [ 'user' => $user, 'foundUsers' => $foundUsers, 'followContext' => 'Following' ]);
    }
}
