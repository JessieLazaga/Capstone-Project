@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-4">
                    <h2 class="card-title">
                       Expiration
                    </h2>
                </div>
            </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Stock Number</th>
                        <th scope="col">Expiry Date</th>
                        <th scope="col"></th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $now = Carbon\Carbon::now();?>
                    @foreach($exp as $expiry)
                        <?php $days_count = Carbon\Carbon::parse($expiry->expiry_date)->diffInDays($now); ?>
                        @if ($expiry->expiry_date < $now)
                            <tr class="table-danger">
                        @elseif(($days_count <= 7) && ($expiry->expiry_date > $now))
                            <tr class="table-warning">
                        @else
                            <tr>
                        @endif
                        @if($days_count <= 7)
                                <td>{{ $expiry->products->name }}</td>
                                <td>{{ $expiry->order_number }}</td>
                                <td>{{ $expiry->expiry_date }}</td>
                                @if(($days_count <= 7) && ($expiry->expiry_date > $now))
                                    <td>will expire in {{$days_count}} Day(s)</td>
                                @else
                                    <td></td>
                                @endif
                                <td></td>
                        @endif
                            </tr>
                        
                    @endforeach
                </tbody>
                
            </table>
            {{ $exp->links() }}
        </div>
    </div>
@endsection