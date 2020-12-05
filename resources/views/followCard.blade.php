<div class="card">
    <div class="card-header">{{ __($followContext) }}
        
    </div>

    <div class="card-body">
        
        <div class="form-group row">
            @csrf
            <div class="col-4">
                @if (!is_null($foundUsers) || empty($foundUsers))
                    @foreach ($foundUsers as $user)
                        <a href="{{route('profile', ['id' => $user->id])}}">
                            @if (empty($user->profileImage))
                            <img src="{{URL('/images/blank-profile-picture.png')}}" height="50" width="50">
                            @else
                                <img src="{{$user->profileImage}}" height="50" width="50">
                            @endif
                            {{ $user->name }}
                        </a>
                    @endforeach
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