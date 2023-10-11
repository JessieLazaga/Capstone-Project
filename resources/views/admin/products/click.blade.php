@extends('admin.admin_master')
@section('admin')
<div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-4">
                    <h2 class="card-title">
                       Products
                    </h2>
                </div>
            </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clickedPoint as $clicked)
                        <tr>
                            <td>{{ $clicked->order_id }}</td>
                            <td>{{ $clicked->quantity }}</td>
                            <td>₱{{ $clicked->price }}</td>
                            <td>{{ $clicked->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
    </div>
@endsection