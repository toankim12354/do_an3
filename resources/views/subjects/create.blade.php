@extends('layouts.app')

@section('title', 'Subject Create')
@section('name_page', 'Subject Create')

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-content">
		<div class="card-header card-header-icon" data-background-color="rose">
			<i class="material-icons">assignment</i>
		</div>
		<form action="{{ route('admin.subject.store') }}" method="POST">
			@csrf
			<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
				<div class="col-md-4 form-group">
					<label>Name</label>
					<input type="text" name="subject" class="input form-control" value="{{ old('subject') }}">
					@error('subject')
						<span class="invalid-feedback text-danger" role="alert">
					        {{ $message }}
					    </span>
					@enderror
				</div>
				<div class="col-md-2 form-group">
					<label>Time (h): </label>
					<input type="number" min="0" name="time" class="input form-control" value="{{ old('time') }}">
					@error('time')
						<span class="invalid-feedback text-danger" role="alert">
					        {{ $message }}
					    </span>
					@enderror
				</div>
				<div class="col-md-4 form-group">
					<input type="submit" value="Submit" class="btn btn-success btn-round">
				</div>
			</div>
		</form>
	</div>
</div>
@stop