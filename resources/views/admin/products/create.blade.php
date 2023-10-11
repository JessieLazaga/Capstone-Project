@extends('admin.admin_master')

@section('admin')
<div>
    <livewire:creates-product :manufacturers="$manufacturers"/> 
</div>
@endsection