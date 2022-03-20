@extends('layouts.app')

@section('name_page', 'Danh sách phân công')

@section('content')
<div>
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-header" data-background-color="blue">
		<strong style="font-size: 20px;">DANH SÁCH PHÂN CÔNG</strong>
	</div>

	<div class="card-content">
		<div class="card-title" style="display: flex; justify-content: space-between; align-items: center;"> 

			{{-- number of row to show --}}
			<div class="col-lg-1 col-md-1 col-sm-1">
				<select class="selectpicker" data-style="select-with-transition" title="Choose gender" id="row">
					<option value disabled>Choose row</option>
					<option value="10" selected>10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select>
			</div>

			{{-- filter grade --}}
			<div class="col-lg-3 col-md-3 col-sm-3">
				<select class="selectpicker" data-style="select-with-transition" title="Choose Grade" data-size="7" id="filterGrade">
					<option value disabled> Choose Grade</option>
					<option value="">All</option>
					@foreach ($grades as $grade)
					<option value="{{ $grade->id }}">
						{{ $grade->name . $grade->yearSchool->name }}
					</option>
					@endforeach
				</select>
			</div>

			{{-- filter subject --}}
			<div class="col-lg-3 col-md-3 col-sm-3">
				<select class="selectpicker" data-style="select-with-transition" title="Choose Subject" data-size="7" id="filterSubject">
					<option value disabled> Choose Subject</option>
					<option value="">All</option>
					@foreach ($subjects as $subject)
					<option value="{{ $subject->id }}">
						{{ $subject->name }}
					</option>
					@endforeach
				</select>
			</div>

			{{-- filter teacher--}}
			<div class="col-lg-3 col-md-3 col-sm-3">
				<select class="selectpicker" data-style="select-with-transition" title="Choose Teacher" data-size="7" id="filterTeacher">
					<option value disabled> Choose Teacher</option>
					<option value="">All</option>
					@foreach ($teachers as $teacher)
					<option value="{{ $teacher->id }}">
						{{ $teacher->name }}
					</option>
					@endforeach
				</select>
			</div>

			{{-- add --}}
			<a href="{{ route('admin.assign.create') }}">
				<button class="btn btn-success btn-round"
				data-toggle="tooltip" title="Add New Assign" data-placement="left" style="padding-left: 14px; padding-right: 14px;">
					<i class="fas fa-plus fa-lg"></i>
				</button>
			</a>
		</div>

		<div class="card-header">
			{{-- alert success --}}
			@if (session('success'))
			<div class="alert alert-dismissable alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 20px;">
					<span aria-hidden="true">&times;</span>
				</button>
				<strong>Success!</strong> {{ session('success') }}
			</div>
			@endif

			@if (session('error'))
			<div class="alert alert-dismissable alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 20px;">
					<span aria-hidden="true">&times;</span>
				</button>
				<strong>Failed!</strong> {{ session('error') }}
			</div>
			@endif
		</div>

		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Grade</th>
						<th>Subject</th>
						<th>Teacher</th>
						<th>Duration</th>
						<th>Start at</th>
						<th>Status</th>
						<th class="text-right">Action</th>
					</tr>
				</thead>
				<tbody>
					@include('admins.assigns.load_index')
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop

@push('script')
<script type="text/javascript">
	$(function() {

		setTimeout(() => {
			$('.alert').remove();
		}, 5000);

		$('[data-toggle="tooltip"]').tooltip();
		
		// alert success when add new assign success
		// if (localStorage.getItem('assign_success') !== null) {
		// 	$('#message').html('').removeClass('alert alert-success');
		// 	$('#message').html(localStorage.getItem('assign_success'))
		// 	.addClass('alert alert-success');
		// 	localStorage.clear();
		// }

		// fetch data when choose row
		$(document).on('change', '#row', function() {
			fetch_page(...get_search());
		});

		// fetch data when choose grade
		$(document).on('change', '#filterGrade', function() {
			fetch_page(...get_search());
		});

		// fetch data when choose subject
		$(document).on('change', '#filterSubject', function() {
			fetch_page(...get_search());
		});

		// fetch data when choose teacher
		$(document).on('change', '#filterTeacher', function() {
			fetch_page(...get_search());
		});

		// fetch data when click search button
		$(document).on('click', '#btnSearch', function(e) {
			e.preventDefault();
		});

		// fetch data when switch page
		$(document).on('click', '.pagination a', function(e) {
			e.preventDefault();

			let page = $(this).attr('href').split('page=')[1];

			fetch_page(...get_search(), page);
		});
	});

	function fetch_page(row = 10, grade, subject, teacher, page = 1) {
		let url =  `{{ route('admin.assign.index') }}?row=${row}&grade=${grade}&subject=${subject}&teacher=${teacher}&page=${page}`;

		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				$('tbody').html(res.html);
				$('[data-toggle="tooltip"]').tooltip();
			},
			error: function(res) {
				let error = res.responseJSON;

				// redirect if unauthenticate
				if (error.hasOwnProperty('url')) {
					window.location.replace(error.url);
				}
			}
		});
	}

	function get_search() {
		return [
		$('#row').val(), 
		$('#filterGrade').val(),
		$('#filterSubject').val(),
		$('#filterTeacher').val()
		];
	}
</script>
@endpush