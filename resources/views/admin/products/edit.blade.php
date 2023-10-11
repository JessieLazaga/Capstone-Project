@extends('admin.admin_master')

@section('admin')
<div>
    <livewire:update :product="$product" :manufacturers="$manufacturers"/> 
</div>
@endsection
