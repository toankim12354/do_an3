@extends('layouts.app')

@section('title', 'Subjects')
@section('name_page', 'Subjects')

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-content">
		<div class="card-header card-header-icon" data-background-color="rose">
			<i class="material-icons">assignment</i>
		</div>
		<div class="card-title" style="display: flex; justify-content: space-between; align-items: center;">
			{{-- search --}}
			<form class="navbar-form navbar-right" id="formSearch">
				<div class="form-group form-search is-empty">
					<input type="text" class="form-control" placeholder=" Search " name="search" value="" id="searchBar">
					<span class="material-input"></span>
				</div>
				<button type="submit" class="btn btn-white btn-round btn-just-icon" id="btnSearch">
					<i class="material-icons">search</i>
					<div class="ripple-container"></div>
				</button>
			</form>
			<a href="{{ route('admin.subject.create') }}" class="btn btn-success btn-round">Add New Subject</a>
		</div>
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
					<th>Subject</th>
					<th>Time (h)</th>
					<th>Create At</th>
					<th>Update At</th>
					<th colspan="2">Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($subjects as $subject)
				<tr>
					<td>{{ $subject->id }}</td>
					<td>{{ $subject->name }}</td>
					<td>{{ $subject->duration }}</td>
					<td>{{ $subject->created_at }}</td>
					<td>{{ $subject->updated_at }}</td>
					<td>
						<a href="{{ route('admin.subject.edit', $subject->id) }}" class="btn btn-info btn-round">
							<i class="material-icons">edit</i>
						</a>
					</td>
					<td>
						<form action="{{ route('admin.subject.destroy', $subject->id) }}" method="POST">
							@csrf
							@method('delete')
							<button type="submit" class="btn btn-danger btn-round">
								<i class="material-icons">close</i>
							</button>
						</form>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<div>{{ $subjects->links() }}</div>
	</div>
</div>
@stop
