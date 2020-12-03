<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Profile extends Model
{
    use HasFactory;

    public static function follow(int $followedUserId) {
        $followingStatus = ['following' => true];
         // Check that the provided user id actually exists.
         $isFollowing = DB::table('follower')->where([['user_id', '=', $followedUserId]], ['follower_id', '=', Auth::user()->id])->count() === 1;
         if (!$isFollowing) {
             // user_id is the user of the account we are about to follow.
             // follower_id is the id of the account that clicked the follow button.
             DB::table('follower')->insert(['user_id' => $followedUserId, 'follower_id' => Auth::user()->id]);
         } else {
            $followingStatus['following'] = false;
            $followId = DB::table('follower')->where([['user_id', '=', Auth::user()->id], ['follower_id', '=', $followedUserId]])->first();
            DB::table('follower')->delete($followId);
         }

         return $followingStatus;
    }

    public static function isFollowing(int $followedUserId) {
         // Check that the provided user id actually exists.
         $isFollowing = DB::table('follower')->where([['user_id', '=', $followedUserId]], ['follower_id', '=', Auth::user()->id])->count() === 1;
         return ['following' => $isFollowing];
    }

    public static function getFollowerCount($id) {
        return DB::table('follower')->where('user_id', $id)->count();
    }

    public static function getFollowingCount($id) {
        return DB::table('follower')->where('follower_id', $id)->count();
    }

    public static function getById($id) {
        return DB::table('users')->where('id', $id)->first();
    }
}
