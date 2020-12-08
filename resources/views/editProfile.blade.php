@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center w-100">
        <form class="m-2 w-100" method="POST" action="{{route('updateProfile')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <h3 for="description" class="card-header w-100 m-1">Profile Picture</h3>
                <label for="image">Choose Image</label>
                <input id="image" type="file" name="image">
            </div>
            
            <div class="form-group">
                <h3 for="description" class="card-header w-100 m-1">Description</h3>
                <textarea type="text" class="form-control" id="description" placeholder="Description" name="description">{{empty($profile) ? "" : $profile->description}}</textarea>
            </div>
            <button type="submit" class="btn btn-dark d-block w-100 mx-auto">Submit</button>
        </form>
    </div>
    
    @if (!empty($created))
    <br/>
    <br/>
    <br/>
    <br/>
    <div class="row justify-content-center w-100">
        <div class="m-2 w-100">
            <div class="form-group">
                <h3 for="name" class="card-header w-100 m-1">Delete Account</h3>
                <p><b>WARNING</b>: This action cannot be undone.</p>
                <button type="button" class="btn btn-danger d-block" data-toggle="modal" data-target="#exampleModal">Delete profile</button>
            </div>
        </div>
    </div>
    @endif

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete your account?</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete your account? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <form class="m-2 w-100" method="POST" action="{{route('deleteUser')}}">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @csrf
                    @method('DELETE')
                    <button type="submit"class="btn btn-danger">Delete</button>
                </form>
            </div>
          </div>
        </div>
      </div>
</div>

@push('user-scripts')
    <script>
        jQuery(function(){
            $('#myModal').on('shown.bs.modal', function () {
                $('#myInput').trigger('focus')
            })
        });
    </script>
@endpush
@endsection
