<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function follow(int $followedUserId) {
        $followingStatus = ['following' => true];
         // Check that the provided user id actually exists.
         $isFollowing = DB::table('follower')->where([['user_id', '=', $followedUserId]], ['follower_id', '=', Auth::user()->id])->count() === 1;
         if (!$isFollowing) {
             // user_id is the user of the account we are about to follow.
             // follower_id is the id of the account that clicked the follow button.
             DB::table('follower')->insert(['user_id' => $followedUserId, 'follower_id' => Auth::user()->id]);
         } else {
            $followingStatus['following'] = false;
            $followId = DB::table('follower')->where([['user_id', '=', $this->id], ['follower_id', '=', $followedUserId]])->first();
            DB::table('follower')->delete($followId);
         }

         return $followingStatus;
    }

    public function isFollowing(int $followedUserId) {
         // Check that the provided user id actually exists.
         $isFollowing = DB::table('follower')->where([['user_id', '=', $followedUserId]], ['follower_id', '=', Auth::user()->id])->count() === 1;
         return ['following' => $isFollowing];
    }
}
