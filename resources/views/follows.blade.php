@extends('layouts.app')

@section('content')
<div class="container">
    @if (is_null($user))
        <div class="row justify-content-center">
            <h2>This profile does not exist</h2>
        </div>
    @else
    <a href="{{route("profile", ['id' => $user->id])}}">
        <h2>{{$user->name}}</h2>
    </a>
    <div class="row justify-content-center" id="tabbed-header">
        <div class="col-md-2" id="user-details">
            <a href="{{route('followers', ['id' => $user->id])}}">Followers</a>
        </div>
        <div class="col-md-2" id="user-details">
            <a href="{{route('following', ['id' => $user->id])}}">Following</a>
        </div>
    </div>
        @include('followCard', ['foundUsers' => $foundUsers])
    @endif
</div>


@endsection
