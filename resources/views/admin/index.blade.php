@extends('admin.admin_master')
@section('admin')

<!-- Top Statistics -->
<div class="row">
    <div class="col-xl-2 col-sm-3">
      <div class="card card-mini mb-2">
        <div class="card-body">
          <h2 class="mb-1">{{$orders_count}}</h2>
          <p>Total Orders</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card card-mini mb-4">
        <div class="card-body">
          <h2 class="mb-1">₱{{number_format($income, 2)}}</h2>
          <p>Total Income</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card card-mini mb-4">
        <div class="card-body">
          <h2 class="mb-1">₱  {{number_format($income_today, 2)}}</h2>
          <p>Income Today</p>
        </div>
      </div>
    </div>
  </div>

  @endsection
