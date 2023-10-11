@extends('admin.admin_master')

@section('admin')
<!DOCTYPE html>
<div class="card card-default">
    <div class="card-header card-header-border-bottom">
        <h2>Place Order</h2>
    </div>
    <div class="card-body">
        <form class="form-pill" method="POST" action="{{ url('/products/order/place') }}">
            @csrf
            <input id="id" name="id" type="hidden" value="{{ request()->route('id') }}" >
            <div class="form-group">
                <label for="order_number">Order Number</label>
                <input id="order_number" class="form-control" type="text" name="order_number" required autofocus autocomplete="order_number" />
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input id="quantity" class="form-control" type="text" name="quantity" required autocomplete="quantity" />
            </div>
            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="btn btn-primary btn-default">
                    Place Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection