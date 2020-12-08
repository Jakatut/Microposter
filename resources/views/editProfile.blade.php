@extends('layouts.app')

@section('content')
<div class="container">
	<a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>

    <div class="row justify-content-center">
        <form class="m-2" method="post" action="{{route('updateProfile')}}" enctype="multipart/form-data">
            <div class="form-group">
                @csrf
                <h3 for="name" class="card-header w-100 m-1">Profile Picture</h3>
            </div>
            <div class="form-group">
                @csrf

                <label for="image">Choose Image</label>
                <input id="image" type="file" name="image">
            </div>
            
            <div class="form-group">
                @csrf
                <h3 for="description" class="card-header w-100 m-1">Description</h3>
                <textarea type="text" class="form-control" id="description" placeholder="Description" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-dark d-block w-75 mx-auto">Edit</button>
        </form>
    </div>
</div>

@endsection
