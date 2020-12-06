@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ url()->previous() }}" class="btn btn-primary mb-4">Back</a>
    <h2>Users</h2>
    <div class="card">
    <div class="card-header">{{ __('Users') }}
        
    </div>
    <link href="{{ asset('css/followCard.css') }}" rel="stylesheet">

    <div class="card-body">
        
        <div class="form-group row">
            @csrf
            <div class="container">
                @if (!is_null($users) || empty($users))
                    @foreach ($users as $user)
                    <div class="row mb-4">
                            <div class="profile-image col-ml-6">
                                <a class="follow_card_user_link" id={{$user['details']->id}}  href="{{route('profile', ['id' => $user['details']->id])}}">
                                    @if (empty($user['details']->profileImage))
                                    <img src="{{URL('/images/blank-profile-picture.png')}}" height="50" width="50">
                                    @else
                                        <img src="{{$user['details']->profileImage}}" height="50" width="50">
                                    @endif
                                </a>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="user-info col">
                                <div class="row">
                                    <a class="follow_card_user_link" href="{{route('profile', ['id' => $user['details']->id])}}">
                                    {{ $user['details']->name }}
                                    </a>
                                </div>
                                <div class="row">
                                    {{ $user['details']->description ?? "" }}
                                </div>
                            </div>
                            <div class="col">
                                <button class="unfollow-user-button" id="unfollow-user-button-{{$user['details']->id}}">{{$user['following'] ? "Unfollow" : "Follow"}}</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No users.</p>
                @endif
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
    </div>
</div>

@push('user-scripts')
<script>
    jQuery(function(){
        $(".unfollow-user-button").on('click', function(event) {
            let userId = event.target.id.replace("unfollow-user-button-", "");
            let baseUrl = "{{url('/')}}".replace("&quot;", "");
            $.ajax({
                type: "POST",
                url: `${baseUrl}/${userId}/toggleFollow`,
                success: function (result) {
                    if (result.following == true) {
                        $(`#unfollow-user-button-${userId}`).html('Unfollow');
                    } else {
                        $(`#unfollow-user-button-${userId}`).html('Follow');
                    }
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                dataType:"json",
            });
        });
    });
</script>
@endpush

@endsection
