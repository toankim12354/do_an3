@extends('layouts.app')

@section('title', 'Year School')
@section('name_page', 'Year School')

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-header card-header-icon" data-background-color="rose">
		<i class="material-icons">assignment</i>
	</div>
	<div class="card-content">
		<form action="{{ route('admin.yearschool.store') }}" method="POST">
			@csrf
			<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
				<div class="col-md-4 form-group">
					<label>Add New</label>
					<input type="text" name="yearschool" class="input form-control" value="{{ old('yearschool') }}">
					@error('yearschool')
						<span class="invalid-feedback" role="alert">
					        <p>{{ $message }}</p>
					    </span>
					@enderror
				</div>
				<div class="col-md-4 form-group">
					<input type="submit" value="Submit" class="btn btn-success btn-round">
				</div>
			</div>
		</form>
		@if(Session::has('message'))
			@if(Session::get('message')['status'] == 1)
				<div class="alert alert-success">{{ Session::get('message')['content'] }}</div>
			@endif
			@if(Session::get('message')['status'] == 0)
				<div class="alert alert-danger">{{ Session::get('message')['content'] }}</div>
			@endif
		@endif
		<table class="table">
			<thead>
				<tr>
				<th>Id</th>
				<th>Year School</th>
				<th>Create At</th>
				<th>Update At</th>
				<th colspan="2">Action</th>
			</tr>
			</thead>
			<tbody>
				@foreach($yearSchools as $yearSchool)
				<tr>
					<td>{{ $yearSchool->id }}</td>
					<td>{{ $yearSchool->name }}</td>
					<td>{{ $yearSchool->created_at }}</td>
					<td>{{ $yearSchool->updated_at }}</td>
					<td>
						<a data-toggle="tooltip" title="Edit" href="{{ route('admin.yearschool.edit', $yearSchool->id) }}" class="btn btn-info btn-round" data-placement="left">
							<i class="material-icons">edit</i>
						</a>
					</td>
					<td>
						<form action="{{ route('admin.yearschool.destroy', $yearSchool) }}" method="POST">
							@csrf
							@method('delete')
							<button data-toggle="tooltip" title="Delete" type="submit" class="btn btn-danger btn-round" data-placement="left">
								<i class="material-icons">close</i>
							</button>
						</form>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
		<div>{{ $yearSchools->links() }}</div>
	</div>
</div>
@push('script')
<script type="text/javascript">
	$('[data-toggle="tooltip"]').tooltip();
</script>
@endpush
@stop
