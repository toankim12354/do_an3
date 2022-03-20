@extends('layouts.app')

@section('title', 'Class Room')
@section('name_page', 'Class Room')

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-content">
		<div class="card-header card-header-icon" data-background-color="rose">
			<i class="material-icons">assignment</i>
		</div>
		<form action="{{ route('admin.classroom.store') }}" method="POST">
		@csrf
		<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
			<div class="col-md-4 form-group">
				<label>Add New Class Room</label>
				<input type="text" name="classroom" class="input form-control" value="{{ old('classroom') }}">
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
	@if(Session::has('message'))
		@if(Session::get('message')['status'] == true)
			<div class="alert alert-success">{{ Session::get('message')['content'] }}</div>
		@endif
		@if(Session::get('message')['status'] == false)
			<div class="alert alert-danger">{{ Session::get('message')['content'] }}</div>
		@endif
	@endif
	<table class="table">
		<thead>
			<tr>
				<th>Id</th>
				<th>Name</th>
				<th>Create At</th>
				<th>Update At</th>
				<th colspan="2">Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($classrooms as $classroom)
			<tr>
				<td>{{ $classroom->id }}</td>
				<td>{{ $classroom->name }}</td>
				<td>{{ $classroom->created_at }}</td>
				<td>{{ $classroom->updated_at }}</td>
				<td>
					<a data-toggle="tooltip" title="Edit" data-placement="left" href="{{ route('admin.classroom.edit', $classroom->id) }}" class="btn btn-info btn-round">
						<i class="material-icons">edit</i>
					</a>
				</td>
				<td>
					<form action="{{ route('admin.classroom.destroy', $classroom->id) }}" method="POST">
						@csrf
						@method('delete')
						<button data-toggle="tooltip" title="Delete" data-placement="left" type="submit" class="btn btn-danger btn-round">
							<i class="material-icons">close</i>
						</button>
					</form>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
<div>{{ $classrooms->links() }}</div>
	</div>
</div>
@push('script')
<script type="text/javascript">
	$('[data-toggle="tooltip"]').tooltip();
</script>
@endpush
@stop
