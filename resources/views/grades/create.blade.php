@extends('layouts.app')

@section('title', 'Grade Create')
@section('name_page', 'Grade Create')

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-header card-header-icon" data-background-color="rose">
		<i class="material-icons">assignment</i>
	</div>
	<div class="card-content">
		<form action="{{ route('admin.grade.store') }}" method="POST">
		@csrf
		<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
			<div class="col-md-4 form-group">
				<label>Year School</label>
				<select name="idyearschool" class="input form-control">
					<option value="">--Select Year School--</option>
					@foreach($yearschools as $yearschool)
					<option value="{{ $yearschool->id }}" <?php
						if (old('idyearschool') == $yearschool->id) {
							echo "selected";
						}
					?>>{{ $yearschool->name }}</option>
					@endforeach
				</select>
				@error('idyearschool')
					<span class="invalid-feedback" role="alert">
				        <p>{{ $message }}</p>
				    </span>
				@enderror
			</div>
			<div class="col-md-4 form-group">
				<label>Name: </label>
				<input type="text" name="grade" class="input form-control" value="{{ old('grade') }}">
				@error('grade')
					<span class="invalid-feedback" role="alert">
				        <p>{{ $message }}</p>
				    </span>
				@enderror
			</div>
			<div class="col-md-2 form-group">
				<input type="submit" value="Submit" class="btn btn-success btn-round">
			</div>
		</div>
	</form>
	</div>
</div>
@stop