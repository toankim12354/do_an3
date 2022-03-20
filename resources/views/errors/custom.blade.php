@extends('layouts.error')

@section('title', __('Not Found'))
@section('code', 'Error')
@section('message')
	@if (session('message'))
		{{ session('message') }}
		{{ Session::keep(['message']) }}
	@else
		{{ __('Has Some Error') }}
	@endif
@stop
