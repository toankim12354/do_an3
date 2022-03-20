@extends('layouts.app')

@section('title', 'Year School Edit')
@section('name_page', 'Year School Edit')

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-header card-header-icon" data-background-color="rose">
		<i class="material-icons">assignment</i>
	</div>
	<div class="card-content">
		<form action="{{ route('admin.yearschool.update', $yearschool->id) }}" method="POST">
		@csrf
		@method('PUT')
		<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
			<div class="col-md-3 form-group">
				<label>Name</label>
				<input class="input form-control" type="text" name="yearschool" value="{{ $yearschool->name }}">
			</div>
			<div class="col-md-2 form-group">
				<input type="submit" value="Update" class="btn btn-success btn-round">
			</div>
		</div>
	</form>
	</div>
</div>
@stop