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
                <div class="col-2">
                    @can('manage products')
                        <a href="{{ route('products.create') }}" class="btn btn-primary">Create Product</a>
                    @endcan
                </div>
            </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Order No.</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Expiry</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($batches as $batch)
                        <tr>
                            <td>{{ $batch->order_number }}</td>
                            <td><img src="{{ asset($batch->products->image) }}" style="height:40px; width:70px"> </td>
                            <td>{{ $batch->products->name }}</td>
                            <td>{{ $batch->quantity }}</td>
                            <td>{{ $batch->expiry_date }}</td>
                            <td>
                                <a href="{{ url('/batch/delete/'.$batch->id) }}" class="btn btn-danger"><i class="mdi mdi-18px mdi-minus"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>

        </div>
    </div>
@endsection