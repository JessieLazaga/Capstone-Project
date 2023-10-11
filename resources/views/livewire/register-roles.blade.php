<div>
<div class="card card-default">
        <div class="card-header card-header-border-bottom">
            <h2>Add A New Role</h2>
        </div>
        <div class="card-body">
            <form action="{{route('roles.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1" class="form-label">Role Name</label>
                    <input type="text" wire:model="role.name" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{ old('name') }}">
                    @error('role.name')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary"
                @if($errors->any() || empty($role['name']))
                    disabled
                @endif
                    wire:loading.attr="disabled"
                >Create</button>
            </form>
        </div>
    </div>
</div>
