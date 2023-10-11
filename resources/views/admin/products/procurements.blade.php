@extends('admin.admin_master')
@section('admin')
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-4">
                    <h2 class="card-title">
                       Product Orders Placed
                    </h2>
                </div>
            </div>
           <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">On Order Quantity</th>
                        <th scope="col">Date of Order</th>
                        <th scope="col">Days since Order</th>
                        <th scope="col">Procurer</th>
                        <th scope="col">Status</th>
                        @can('manage stocks')
                            <th scope="col">Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                <?php $now = Carbon\Carbon::now();?>
                    @foreach($procure as $proc)
                        <?php $days_count = Carbon\Carbon::parse($proc->created_at)->diffInDays($now); ?>
                        <tr>
                            <td>{{ $proc->products->name }}</td>
                            <td>{{ $proc->quantity }}</td>
                            <td>{{ $proc->created_at }}</td>
                            <td>{{$days_count}} Day(s)</td>
                            <td>{{$proc->manufacturers->name}}</td>
                            <td>{!! $proc->status ? '<span class="badge badge-success">Delivered</span>' : '<span class="badge badge-secondary">Ordered</span>' !!}
                            
                            <!--<td>
                                <a href="{{ route('procurement.submit', $proc) }}" class="btn btn-info"><i class="mdi mdi-18px mdi-plus"></i></a>
                            </td>-->
                            @if(!$proc->confirmed)
                            <td>
                                <button type="button" class="btn btn-primary confirmed-{{ $proc->id }}" @if(!$proc->status)  disabled    @endif>
                                    Confirm Delivery
                                </button>
                            </td>
                            @else
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-{{ $proc->id }}">
                                    Set Expiry
                                </button>
                            </td>
                            @endif
                            
                        </tr>
                        <div class="modal fade" id="modal-{{ $proc->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <div class="modal-body">
                                <form class="form-pill" action="{{ url('/procure/submit') }}" method="POST">
                                    @csrf
                                    <input id="id" name="id" type="hidden" value="{{ $proc->id }}"/>
                                    <div class="form-group">
                                        <label for="batch">Batch No.</label>
                                        <input id="batch" class="form-control" name="batch" type="number" min = "0"  required autocomplete="batch" />
                                        <span id="#error-message" class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="expiry_date-{{ $proc->id }}">Expiry</label>
                                        <input id="expiry_date-{{ $proc->id }}" class="form-control" type="date" name="expiry_date" required autofocus autocomplete="expiry_date-{{ $proc->id }}" />
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input id="quantity" class="form-control" type="number" min = "1" max="{{$proc->quantity}}" name="quantity" oninput="validity.valid||(value='');" required autocomplete="quantity" />
                                    </div>
                                    <div class="flex items-center justify-end mt-4">
                                        <button id="place-order-button" type="submit" class="btn btn-primary btn-default">
                                            Place Order
                                        </button>
                                    </div>
                                </form>
                                </div>
                                
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
            {{ $procure->links() }}
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#batch').on('blur', function() {
            $.ajax({
            url: '/check-batch-number',  // URL to send the request to
            type: 'POST',  // HTTP method
            data: {  // Data to send to the server
                batch: $(this).val()
            },
            success: function(response) {  // Callback function to be called on success
                if (response.unique) {
                // The batch number is unique, do something (e.g., show a success message)
                } else {
                    $('#error-message').text('The batch number is not unique.');
                    $('#place-order-button').prop('disabled', true);
                }
            }
            });
        });
            const today = new Date();
            const tomorrow = new Date();
            tomorrow.setDate(today.getDate() + 1);
            @foreach($procure as $proc)
                document.getElementById('expiry_date-{{ $proc->id }}').min = tomorrow.toISOString().split('T')[0];
                $('.confirmed-{{ $proc->id }}').click(function() {
                $.ajax({
                    url: "/confirm-delivery",
                    method: "POST",
                    data: {
                        procure_id: {{ $proc->id }},
                        product_id: {{ $proc->product_id }},
                        _token: '{{ csrf_token() }}'
                    }
                });
            });
            @endforeach
    } );
</script>
@endsection