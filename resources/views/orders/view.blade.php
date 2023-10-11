@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">

            <div class="row justify-content-between">
                <div class="col-4">
                    <h2 class="card-title">
                        Order #{{$orders->id}}
                    </h2>
                </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Products</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($orders->products as $item)
                    <tr style="color: #5D5755">
                        <td>{{$item->products->name}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>₱ {{number_format($item->price, 2)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection