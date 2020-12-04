@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Posts Dashboard') }}
                	
                </div>

                <div class="card-body">
                	
                	<div class="form-group row">
	                	
                        @csrf
                        
                    	<div class="col-4 offset-8">
							<a type="submit" class="btn btn-primary" href="{{ route('newPost') }}">
							    {{ __('Create New') }}
							</a>
						</div>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @else
                    	{{ __('POSTS HERE') }}
                    @endif

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
