<div class="card">
    <div class="card-header">{{ __('Followers') }}
        
    </div>

    <div class="card-body">
        
        <div class="form-group row">
            @csrf
            <div class="col-4 offset-8">
                @if (!is_null($followers))
                    @foreach ($followers as $follower)
                        <div>  
                            {{ $follower->name }}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @else
        @endif

        
    </div>
</div>