@extends('admin.admin_master')

@section('admin')
@if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ session('error') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@if(Auth::guard('web')->check())
    <div class="card card-default">
        
        <div class="card-header card-header-border-bottom">
            <h2>User Change Password</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}" class="form-pill">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlPassword3">Current Password</label>
                    <input type="password" name="oldpassword" class="form-control" id="current_password" placeholder="Current Password">
                    @error('oldpassword')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="exampleFormControlPassword3">New Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Confirm Password">
                    @error('password')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="exampleFormControlPassword3">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="New Password">
                    @error('password_confirmation')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-default">Submit</button>
            </form>
        </div>
    </div>
    @elseif(Auth::guard('admin')->check())
    <div class="card card-default">
        
        <div class="card-header card-header-border-bottom">
            <h2>Admin Change Password</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('adminpassword.update') }}" class="form-pill">
                
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlPassword3">Current Password</label>
                    <input type="password" name="oldpassword" class="form-control" id="current_password" placeholder="Current Password">
                    @error('oldpassword')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="exampleFormControlPassword3">New Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Confirm Password">
                    @error('password')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="exampleFormControlPassword3">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="New Password">
                    @error('password_confirmation')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-default">Submit</button>
            </form>
        </div>
    </div>
    @endif
    @endsection