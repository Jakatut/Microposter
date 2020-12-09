@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Users</h2>
    <div class="card">
    <div class="card-header">{{ __('Users') }}
        
    </div>
    <link href="{{ asset('css/followCard.css') }}" rel="stylesheet">

    <div class="card-body">
        
        <div class="form-group row">
            @csrf
            <div class="container">
                @if (!empty($users))
                    @foreach ($users as $user)
                    <div class="row mb-4">
                            <div class="profile-image col-ml-6">
                                <a class="follow_card_user_link" id={{$user['details']->id}}  href="{{route('profile', ['id' => $user['details']->id])}}">
                                    <img src="{{$user['profileImageURL']}}" height="100" width="100">
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
                                    {{ 'Joined on ' . substr($user['details']->created_at, 0, strrpos($user['details']->created_at, ' '))}}
                                </div>
                            </div>
                            {{dd($user['details'])}}
                            @if (Auth::user()->id !== $user['details']->id)
                                <div class="col">
                                    <div class="unfollow-user-button btn btn-primary" id="unfollow-user-button-{{$user['details']->id}}">{{$user['following'] ? "Unfollow" : "Follow"}}</div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="row mb-4 text-center w-100">
                        <div class="col">
                            Could not find any users.
                        </div>
                    </div>
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
