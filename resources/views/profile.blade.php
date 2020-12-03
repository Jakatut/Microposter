@extends('layouts.app')

@section('content')
<div class="container">
    @if (empty($user))
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                    <p>This profile does not exist</p>
            </div>
        </div>
    </div>
    @else
    <div class="row justify-content-center">
        <div class="col-md-2" id="user-details">
            @if (empty($user->profileImage))
                <img src="{{URL('/images/blank-profile-picture.png')}}" height="100" width="100">
            @else
                <img src="{{$user->profileImage}}" height="100" width="100">
            @endif
            <p>{{$user->name}}</p>
            <p>Following: 0</p>
            <p>Followers: 0</p>
            
            @if (Auth::user()->id !== $user->id)
                <button>Follow</button>
            @endif
        </div>
        <div class="col-md-8">
            <div class="card">
                    Feed
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
