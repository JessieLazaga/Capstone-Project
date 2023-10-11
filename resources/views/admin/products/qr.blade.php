@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-4">
                    <h2 class="card-title">
                        QR
                    </h2>
                </div>
            </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Barcode</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->barcode }}</td>
                            <td>
                                <a href="{{ route('generateqr', $product) }}" class="btn btn-info">Download QR</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
            {{ $products->links() }}
        </div>
    </div>
@endsection