@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Posts Dashboard') }}
                	
                </div>

                <div class="card-body">

					<form action="{{ route('createNewPost')}}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="form-group">
							<label for="exampleFormControlTextarea1">Whats on your mind?</label>
						<textarea class="form-control" rows="3" style="resize: none" maxlength="150" name="content"></textarea>
						</div>
						
						<div class="form-group row">
						    <div class="col-4 offset-10">
						      <button type="submit" class="btn btn-primary">Post</button>
						    </div>
					  </div>
					</form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
