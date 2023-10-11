@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
                <div class="col-6">
                    <h2 class="card-title">
                       Products
                    </h2>
                </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Average Lead Time</th>
                        <th scope="col">Maximum Lead Time</th>
                        <th scope="col">Par Level</th>
                        <th scope="col">Quantity</th>
                        @can('manage stocks')
                            <th scope="col">Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <?php 
                                $Avg = DB::table('dayscounts')->where('product_id', $product->id)->avg('days');
                                $leadAvg = number_format($Avg, 0);
                                $leadMax = DB::table('dayscounts')->where('product_id', $product->id)->max('days');
                            ?>
                            --<td><img src="{{ asset($product->image) }}" style="height:40px; width:70px"> </td>
                            <td>{{ $product->name }}</td>
                            @if(!$leadAvg || $leadAvg < 0)
                                <td>Not Available</td>
                            @else
                                <td>{{ $leadAvg }} Day(s)</td>
                            @endif
                            @if(!$leadMax || $leadMax < 0)
                                <td>Not Available</td>
                            @else
                                <td>{{ $leadMax }} Day(s)</td>
                            @endif
                            @if(!$product->par_level || $product->par_level < 0)
                                <td>Not Available</td>
                            @else
                                <td>{{ round($product->par_level, 0) }}</td>
                            @endif
                            <td>{{ $product->quantity }}</td>
                            @can('manage stocks')
                            <td>
                                <a href="{{ route('orderpanel', $product) }}" class="btn btn-info"><i class="mdi mdi-18px mdi-plus"></i></a>
                            </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    </div>
@endsection