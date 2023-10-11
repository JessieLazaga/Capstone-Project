@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">

            <div class="row justify-content-between">
                <div class="col-4">
                    <h2 class="card-title">
                        Order History
                    </h2>
                </div>
            </div>
            <div class="col-md-7">

                </div>
                <div class="col-md-12">
                    <form action="{{route('orders.index')}}" method="get">
                        <div class="row">
                            <div class="col-md-7"></div>
                            
                            <div class="col-md-2">
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-outline-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">User</th>
                        <th scope="col">Total</th>
                        <th scope="col">Received</th>
                        <th scope="col">Date</th>
                        <th scope="col">View Orders</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($orders as $order)
                    <tr style="color: #5D5755">
                        <td>{{$order->id}}</td>
                        <td>{{$order->getUserid()}}</td>
                        <td>₱ {{$order->formattedPerm()}}</td>
                        <td>₱ {{$order->formattedReceived()}}</td>
                        <td>{{$order->created_at}}</td>
                        <td><a href="{{ url('view/'.$order->id) }}" class="btn btn-primary">View</button></td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                    <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col">₱{{number_format($total, 2)}}</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </tfoot>
                {{ $orders->links() }}
            </table>
            @if(Auth::guard('admin')->check())
            <div class="col-md-12">
                    <form action="{{route('orders.download')}}" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-md-5"></div>
                            
                            <div class="col-md-2">
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-primary">Generate Report PDF</button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
        </div>
    </div>
@endsection