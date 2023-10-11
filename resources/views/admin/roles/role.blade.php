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
                <div class="col-4-sm">
                    @can('manage users')
                        <a href="{{ url('/registration') }}" class="btn btn-primary">Register User</a>
                        <a href="{{ url('/registration/manufacturer') }}" class="btn btn-primary">Register a Manufacturer</a>
                    @endcan
                </div>
            </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        @can('manage users')
                            @foreach($roles as $role)
                                @if($role->name != 'super-admin' && $role->name != 'user')
                                    <th scope="col">{{ $role->name }}</th>
                                @endif
                            @endforeach
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name ?: 'Manufacturer' }}</td>
                            @foreach($roles as $role)
                                @if($role->name != 'super-admin' && $role->name != 'user')
                                    <td>
                                        <input type="checkbox" id="{{ $user->id }}-{{ $role->id }}" name="{{ $user->id }}-{{ $role->id }}" @if($user->roles->contains($role)) checked @endif>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                
            </table>

        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function(){
        @foreach($users as $user)
            @foreach($roles as $role)
                $('#{{ $user->id }}-{{ $role->id }}').click(function()
                {
                    if($(this).is(':checked')){
                        $.ajax({
                            url: "/assign/checked",
                            method: "POST",
                            data: {
                                user: {{ $user->id }},
                                role: {{ $role->id }},
                                _token: '{{ csrf_token() }}'
                            }
                        });
                    }
                    else{
                        $.ajax({
                            url: "/assign/unchecked",
                            method: "POST",
                            data: {
                                user: {{ $user->id }},
                                role: {{ $role->id }},
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