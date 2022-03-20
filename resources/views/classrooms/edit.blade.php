@extends('layouts.app')

@section('title', 'Class Room Edit')
@section('name_page', 'Class Room Edit')

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-content">
		<div class="card-header card-header-icon" data-background-color="rose">
			<i class="material-icons">assignment</i>
		</div>
		<form action="{{ route('admin.classroom.update', $classroom->id) }}" method="POST">
		@csrf
		@method('PUT')
		<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
			<div class="col-md-4 form-group">
				<label>Edit Class Room</label>
				<input type="text" name="classroom" class="input form-control" value="{{ $classroom->name }}">
				@error('classroom')
					<span class="invalid-feedback text-danger" role="alert">
				        {{ $message }}
				    </span>
				@enderror
			</div>
			<div class="col-md-2 form-group">
				<input type="submit" value="Add New" class="btn btn-success btn-round">
			</div>
		</div>
	</form>
	</div>
</div>
@stop