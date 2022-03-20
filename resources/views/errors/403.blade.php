@extends('layouts.error')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message')
	@if (isset($exception))
		{{ __($exception->getMessage() ?: 'Forbidden') }}
	@elseif(session('message'))
		{{ __(session('message')) }}
		{{ Session::keep(['message']) }}
	@endif
@stop
