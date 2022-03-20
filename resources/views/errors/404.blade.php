@extends('layouts.error')

@section('title', __('Not Found'))
@section('code', '404')
@section('message')
	@if (session('message'))
		{{ session('message') }}
		{{ Session::keep(['message']) }}
	@else
		{{ __('Not Found') }}
	@endif
@stop
