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
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Barcode</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        @can('manage products')
                            <th scope="col">Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td><img src="{{ asset($product->image) }}" style="height:40px; width:70px"> </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->barcode }}</td>
                            <td>₱{{ $product->price }}</td>
                            <td>{{ $product->quantity }}</td>
                            @can('manage products')
                            <td>
                                <div class="dropdown show">
                                    <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="{{ route('products.edit', $product) }}">Edit</a>
                                        <a class="dropdown-item" href="{{ route('products.setBatch', $product) }}">Set Batch</a>
                                        <a class="dropdown-item" href="{{ url('products/delete/'.$product->id) }}" onclick="return confirm('Are you sure to delete {{$product->name}}?')" >Delete</a>
                                        <a class="dropdown-item" href="$" data-toggle="modal" data-target="#exampleModal-{{$product->id}}">Generate Chart</a>
                                    </div>
                                </div>
                            </td>
                            @endcan
                        </tr>
                        <div class="modal fade" id="exampleModal-{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Generate Chart</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ url('products/chart/'.$product->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <label for="from">From:</label>
                                                <input type="date" id="from" name="fromDate">
                                                <label for="from">To:</label>
                                                <input type="date" id="to" name="toDate">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="inputGroupSelect01">Group By</label>
                                                    </div>
                                                    <select class="custom-select" id="inputGroupSelect01" name="groupBy">
                                                        <option value="1" selected>Daily</option>
                                                        <option value="2">Weekly</option>
                                                        <option value="3">Monthly</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Generate</button>
                                            </div>
                                        </form>
                                </div>
                            </div>
                    @endforeach
                </tbody>
                
            </table>
            {{ $products->links() }}
        </div>
    </div>
@endsection