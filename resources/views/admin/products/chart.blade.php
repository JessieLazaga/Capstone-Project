@extends('admin.admin_master')
@section('admin')
    <!DOCTYPE html>
    <html lang="en" dir="ltr">
        {!! $chart1->renderChartJsLibrary() !!}
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <h1>{{ $chart1->options['chart_title'] }}</h1>
                            {!! $chart1->renderHtml() !!}
                        </div>
                    </div>
                </div>
            </div>
            {!! $chart1->renderJs() !!}
            @isset($dated)
                {{$dated}}
            @endisset
        </div>
    </html>
@endsection
