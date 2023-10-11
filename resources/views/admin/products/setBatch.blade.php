@extends('admin.admin_master')
@section('admin')
<div>
    <div class="card card-default">
        <div class="card-header card-header-border-bottom">
            <h2>Update Product</h2>
        </div>
        <div class="card-body">
            
            <form action="{{ route('products.updateBatch') }}" method="POST">
                @csrf
                <input name="id" id="id" type="hidden" value="{{$product->id}}"/>
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="batch">Select Batch</label>
                        </div>
                        <select class="custom-select" id="batch" name="batch">
                            @foreach($batches as $batch)
                                <option value="{{$batch->order_number}}">{{$batch->order_number}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
