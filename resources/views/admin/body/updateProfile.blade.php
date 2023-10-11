@extends('admin.admin_master')

@section('admin')
@if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
    @endif
    @if(Auth::guard('web')->check())
        <div class="card card-default">
            
            <div class="card-header card-header-border-bottom">
                <h2>User Profile Update</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" class="form-pill">
                    
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlPassword3">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user['name'] }}">
                        <!--@error('oldpassword')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror-->
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlPassword3">Email</label>
                        <input type="text" name="email" class="form-control" value="{{ $user['email'] }}">
                        <!--@error('oldpassword')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror-->
                    </div>
                    <button type="submit" class="btn btn-primary btn-default">Update</button>
                </form>
            </div>
        </div>
    @elseif(Auth::guard('admin')->check())
    <div class="card card-default">
            
            <div class="card-header card-header-border-bottom">
                <h2>Admin Profile Update</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('adminprofile.update') }}" class="form-pill">
                    
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlPassword3">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $admin['name'] }}">
                        <!--@error('oldpassword')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror-->
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlPassword3">Email</label>
                        <input type="text" name="email" class="form-control" value="{{ $admin['email'] }}">
                        <!--@error('oldpassword')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror-->
                    </div>
                    <button type="submit" class="btn btn-primary btn-default">Update</button>
                </form>
            </div>
        </div>
    @endif
@endsection