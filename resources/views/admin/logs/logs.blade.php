@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-3">
                    <h2 class="card-title">
                        Activity Logs
                    </h2>
                </div>
            </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->created_at}}</td>
                            <td>{{ $log->username}}</td>
                            <td>{{ $log->activity}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $logs->links() }}
        </div>
    </div>
@endsection