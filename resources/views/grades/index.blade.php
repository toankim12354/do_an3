@extends('layouts.app')

@section('title', 'Grades')
@section('name_page', 'Grades')

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-header card-header-icon" data-background-color="rose">
		<i class="material-icons">assignment</i>
	</div>
	<div class="card-content">
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
			<a href="{{ route('admin.grade.create') }}" class="btn btn-success btn-round">Add New Grade</a>
		</div>
		@if(Session::has('message'))
			@if(Session::get('message')['status'] == 1)
				<div class="alert alert-success">{{ Session::get('message')['content'] }}</div>
			@endif
			@if(Session::get('message')['status'] == 0)
				<div class="alert alert-danger">{{ Session::get('message')['content'] }}</div>
			@endif
		@endif
		@foreach ($yearSchools as $yearSchool)
			<div class="info">
                <a data-toggle="collapse" href="#YearSchool{{ $yearSchool->id }}" class="collapsed">
                    <h2 class="alert">{{ $yearSchool->name }}
                    	@if(count($yearSchool->grades))
                    		<b class="caret"></b>
                    	@endif
                    </h2>
                </a>
                <div class="clearfix"></div>
                <div class="collapse" id="YearSchool{{ $yearSchool->id }}">
                    @if(count($yearSchool->grades))
					<table class="table">
						<thead>
							<tr>
								<th>Id</th>
								<th>Grade</th>
								<th>Create At</th>
								<th>Update At</th>
								<th colspan="2">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($yearSchool->grades as $grade)
							<tr>
								<td>{{ $grade->id }}</td>
								<td>{{ $grade->name }}</td>
								<td>{{ $grade->created_at }}</td>
								<td>{{ $grade->updated_at }}</td>
								<td>
									<a data-toggle="tooltip" title="Edit" data-placement="left" href="{{ route('admin.grade.edit', $grade->id) }}" class="btn btn-info btn-round">
										<i class="material-icons">edit</i>
									</a>
								</td>
								<td>
									<form action="{{ route('admin.grade.destroy', $grade->id) }}" method="POST">
										@csrf
										@method('delete')
										<button data-toggle="tooltip" title="Delete" data-placement="left" style="submit" class="btn btn-danger btn-round">
											<i class="material-icons">close</i>
										</button>
									</form>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				@endif
                </div>
            </div>
		@endforeach
		<div>
			{{ $yearSchools->links() }}
		</div>
	</div>
</div>
@push('script')
<script type="text/javascript">
	$('[data-toggle="tooltip"]').tooltip();
</script>
@endpush
@stop
