@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-4">
                    <h2 class="card-title">
                       Orders
                    </h2>
                </div>
            </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Order No.</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($procurements as $procurement)
                        <tr>
                            <td>{{ $procurement->order_number }}</td>
                            <td><img src="{{ asset($procurement->products->image) }}" style="height:40px; width:70px"> </td>
                            <td>{{ $procurement->products->name }}</td>
                            <td>{{ $procurement->quantity }}</td>
                            <td><button class="btn btn-primary deliver-{{ $procurement->id }}">Delivered</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        @foreach($procurements as $procurement)
            $('.deliver-{{ $procurement->id }}').click(function() {
                $.ajax({
                    url: "/manufacturer/deliver",
                    method: "POST",
                    data: {
                        procure_id: {{ $procurement->id }},
                        _token: '{{ csrf_token() }}'
                    }
                });
            });
        @endforeach
    });
    </script>
@endsection