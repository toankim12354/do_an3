@extends('layouts.app')

@section('title', __('student'))

@section('name_page', 'Danh sách sinh viên')

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-header" data-background-color="blue">
		<strong style="font-size: 20px;">DANH SÁCH SINH VIÊN</strong>
	</div>
	<div class="card-content">
		<div class="btn-group">
		  <a type="button" class="btn btn-info utility-btn" data-toggle="tooltip" data-placement="right" title="thêm giáo vụ mới" href="{{ route('admin.student-manager.create') }}">
		  	THÊM
		  </a>

		  <a type="button" class="btn btn-success utility-btn" data-toggle="tooltip" data-placement="right" title="nhập danh sách giáo vụ từ file excel" href="{{ route('admin.student-manager.form_import') }}">
		  	NHẬP
		  </a>

		  <a type="button" class="btn btn-warning utility-btn" data-toggle="tooltip" data-placement="right" title="xuất danh sách giáo vụ ra file excel" href="{{ route('admin.student-manager.export_excel') }}">
		  	XUẤT
		  </a>
		</div>
		<div class="card-title" style="display: flex; justify-content: space-between; align-items: center;"> 

			{{-- number of row to show --}}
			<div class="col-lg-1 col-md-1 col-sm-1">
				<select class="selectpicker" data-style="select-with-transition" title="Số hàng" id="row">
					<option value disabled>Số hàng</option>
					<option value="10" selected>10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select>
			</div>

			{{-- filter grade --}}
			<div class="col-lg-2 col-md-2 col-sm-2">
				<select class="selectpicker" data-style="select-with-transition" title="Lớp " data-size="7" id="filterGrade">
					<option value disabled> Lớp </option>
					<option value="">Tất cả </option>
					@foreach ($grades as $grade)
					<option value="{{ $grade->id }}">
						{{ $grade->name }}
					</option>
					@endforeach
				</select>
			</div>
			
			{{-- filter gender --}}
			<div class="col-lg-2 col-md-2 col-sm-2">
				<select class="selectpicker" data-style="select-with-transition" title="Giới tính" data-size="7" id="filterGender">
					<option value disabled> Giới tính </option>
					<option value="">Tất cả </option>
					<option value="1">Nam  </option>
					<option value="0">Nữ </option>
				</select>
			</div>

			{{-- filter status --}}
			<div class="col-lg-2 col-md-2 col-sm-2">
				<select class="selectpicker" data-style="select-with-transition" title="Trạng thái " data-size="7" id="filterStatus">
					<option value disabled> Trạng thái </option>
					<option value="">Tất cả </option>
					<option value="1">Active</option>
					<option value="0">Inactive</option>
				</select>
			</div>

			{{-- search --}}
			<form class="navbar-form navbar-right" id="formSearch">
				<div class="form-group form-search is-empty">
					<input type="text" class="form-control" placeholder=" Tìm kiếm  " name="search" value="" id="searchBar">
					<span class="material-input"></span>
				</div>
				<button type="submit" class="btn btn-white btn-round btn-just-icon" id="btnSearch">
					<i class="material-icons">search</i>
					<div class="ripple-container"></div>
				</button>
			</form>
		</div>
		{{-- alert success --}}
		@if (session('success'))
		<div class="alert alert-dismissable alert-success">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 20px;">
				<span aria-hidden="true">&times;</span>
			</button>
			<strong>Success!</strong> {{ session('success') }}
		</div>
		@endif

		{{-- alert error --}}
		@if (session('error'))
		<div class="alert alert-dismissable alert-danger">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 20px;">
				<span aria-hidden="true">&times;</span>
			</button>
			<strong>Error!</strong> {{ session('error') }}
		</div>
		@endif

		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>TÊN </th>
						<th>MSV</th>
						<th>EMAIL </th>
						<th>ĐIỆN THOẠI </th>
						<th>NGÀY SINH </th>
						<th>ĐỊA CHỈ </th>
						<th>GIỚI TÍNH </th>
						<th>LỚP </th>
						<th>TRẠNG THÁI </th>
						<th>HÀNH ĐỘNG </th>
					</tr>
				</thead>
				<tbody>
					@include('admins.students.load_index')
				</tbody>
			</table>

		</div>
	</div>
</div>
@stop
@push('script')
<script type="text/javascript">
	$(function() {
		// hide alert after time
		setTimeout(() => {
			$('.alert').remove();
		}, 5000);

		$('[data-toggle="tooltip"]').tooltip();

		// fetch data when type in search bar
		$(document).on('keyup', '#searchBar', function() {
			fetch_page(...get_search());
		});

		// fetch data when choose grade
		$(document).on('change', '#filterGrade', function() {
			fetch_page(...get_search());
		});

		// fetch data when choose row
		$(document).on('change', '#row', function() {
			fetch_page(...get_search());
		});

		// fetch data when choose gender
		$(document).on('change', '#filterGender', function() {
			fetch_page(...get_search());
		});

		// fetch data when choose status
		$(document).on('change', '#filterStatus', function() {
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

	function fetch_page(row = 10, grade, gender, status, search, page = 1) {
		let url =  `{{ route('admin.student-manager.index') }}?row=${row}&grade=${grade}&gender=${gender}&status=${status}&search=${search}&page=${page}`;

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
			$('#filterGender').val(),
			$('#filterStatus').val(),
			$('#searchBar').val()
		];
	}
</script>
@endpush