<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Profile extends Model
{
    use HasFactory;

    /**
     * Submits the 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public static function toggleFollow(int $followedUserId) {
        $followingStatus = ['following' => true];

        $currentUserId = Auth::user()->id;

        // Check that the provided user id actually exists.
        $follower = User::find($followedUserId)->followers()->where('follower_id', $currentUserId);
        if ($follower->count() === 0) {
            // user_id is the user of the account we are about to follow.
            // follower_id is the id of the account that clicked the follow button.
            $follower = new Follower();
            $follower->user_id = $followedUserId;
            $follower->follower_id = $currentUserId;
            $follower->save();
            
        } else {
            $follower->delete();
            $followingStatus = ['following' => false];
        }

        $followingStatus = array_merge($followingStatus, ['followingCount' => Profile::getFollowingCount($followedUserId), 'followerCount' => Profile::getFollowerCount($followedUserId)]);
        return $followingStatus;
    }

    public static function isFollowing(int $followedUserId) {
        $currentUserId = Auth::user()->id;
        // Check that the provided user id actually exists.
        $user = User::find($followedUserId);
        $isFollowing = false;
        if ($user) {
            $isFollowing = $user->followers()->where('follower_id', $currentUserId)->count() === 1;
        }
        return ['following' => $isFollowing];
    }

    public static function getFollowerCount($id) {
        return Follower::where('user_id', $id)->count();
    }

    public static function getFollowingCount($id) {
        return Follower::where('follower_id', $id)->count();
    }

    public static function getById($id) {
        return User::where('id', $id)->first();
    }
}
