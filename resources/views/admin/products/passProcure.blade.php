@extends('admin.admin_master')

@section('admin')
<!DOCTYPE html>
<html lang="en">
  <script src="{{ asset('backend/assets/plugins/nprogress/nprogress.js') }}"></script>
</head>

<div class="card card-default">
    <div class="card-header card-header-border-bottom">
        <h2>Place Order</h2>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'procure.submit', 'class' => 'form-horizontal']) !!}
        <div id="stock_fields">
            <div class="stock-fields-wrapper">
                <div class="form-group">
                    {!! Form::label('expiry_date', 'Expiry Date:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                    {!! Form::text('expiry_date[]', null, ['class' => 'form-control datepicker', 'name' => 'expiry_date[]']) !!}
                </div>
                </div>
                <div class="form-group">
                    {!! Form::label('quantity', 'Quantity:', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-10">
                        {!! Form::text('quantity[]', null, ['class' => 'form-control', 'name' => 'quantity[]' ]) !!}
                    </div>
                </div>
            
                <div class="form-group">
                    <button class="btn btn-danger remove-button">Remove</button>
                </div>
            </div>
        </div>
        <button class="btn btn-primary" id="add-stock-button">Add</button>
            {!! Form::submit('Divide Stock', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.datepicker').datepicker({
            orientation: "bottom",
            format: "yyyy-mm-dd",
        });
        var counter = 1;
        if (counter <= 1) {
            $('.remove-button').attr('disabled', true);
        }
        $(document).on('click', '.remove-button', function(event) {
            event.preventDefault();
            counter--;
            if (counter <= 1) {
            $('.remove-button').attr('disabled', true);
            }
            $(this).parent().parent().remove();
        });
        $('#add-stock-button').click(function(event) {
            event.preventDefault();
            counter++;
            if (counter > 1) {
                $('.remove-button').attr('disabled', false);
            }
            var fields = $('.stock-fields-wrapper:first').clone();
            fields.find('input').val('');
                fields.find('.form-control[type="date"]').each(function() {
                    $(this).datepicker();
                });
                
                $('#stock_fields').append(fields);
        });
    });
</script>
@endsection