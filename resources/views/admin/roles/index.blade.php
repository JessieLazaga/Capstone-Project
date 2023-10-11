@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-4">
                    <h2 class="card-title">
                       Roles
                    </h2>
                </div>
                <div class="col-2">
                    @can('manage users')
                        <a href="{{ route('roles.register') }}" class="btn btn-primary">Register Role</a>
                    @endcan
                </div>
            </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        @can('manage users')
                            @foreach($permissions as $permission)
                            <th scope="col">{{ $permission->name }}</th>
                            @endforeach
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        @if($role->name != 'super-admin' && $role->name != 'user')
                        <tr>
                            <td>{{ $role->name }}</td>
                            @foreach($permissions as $permission)
                                <td>
                                    <input type="checkbox" id="{{ $role->id }}-{{ $permission->id }}" name="{{ $role->id }}-{{ $permission->id }}" @if($role->permissions->contains($permission)) checked @endif>
                                </td>
                            @endforeach
                        </tr>
                        @endif
                    @endforeach
                </tbody>
                
            </table>

        </div>
    </div>
    
<script type="text/javascript">
    $(document).ready(function(){
        @foreach($roles as $role)
            @foreach($permissions as $permission)
                $('#{{ $role->id }}-{{ $permission->id }}').click(function()
                {
                    if($(this).is(':checked')){
                        $.ajax({
                            url: "/roles/checked",
                            method: "POST",
                            data: {
                                role: {{ $role->id }},
                                permission: {{ $permission->id }},
                                _token: '{{ csrf_token() }}'
                            }
                        });
                    }
                    else{
                        $.ajax({
                            url: "/roles/unchecked",
                            method: "POST",
                            data: {
                                role: {{ $role->id }},
                                permission: {{ $permission->id }},
                                _token: '{{ csrf_token() }}'
                            }
                        });
                    }
                });
            @endforeach
        @endforeach
    });
</script>
@endsection