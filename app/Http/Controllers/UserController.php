<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
    public function index(Request $request) {
        $query = $request->get('query');
        if ($query !== null) {
            // If the user has made a search, redirect to the proper control.
            return $this->searchUser($request, $query);
        }

        // User profile requests (no id)
        $users = User::where('id', '!=', auth()->id())->get();
        $foundUsers = [];
        foreach($users as $user) {
            $following = Follower::isFollowing($user->id)['following'];
            array_push($foundUsers, ['details' => $user, 'following' => $following]);
        }
        
        return view('users', ['users' => $foundUsers]);
    }

    public function searchUser(Request $request, $query) {
        // User profile requests (no id)
        if ($query === null) {
            return $this->index($request, );
        }

        $where = $this->getSQLSearchFilter($query);
        $users = User::where($where['field'], $where['op'], $where['value'])->get();
        $foundUsers = [];
        foreach($users as $user) {
            $following = Follower::isFollowing($user->id)['following'];
            array_push($foundUsers, ['details' => $user, 'following' => $following]);
        }
        
        return view('users', ['users' => $foundUsers]);
    }

    public function getSQLSearchFilter($query) {
        $filter = ['field' => 'id', 'op' => '=', 'value' => $query];
        if (!is_numeric($query)) { 
            $filter['field'] = 'name';
            $filter['op'] = 'like';
            $filter['value'] = "%${query}%";
        }

        return $filter;
    }
}