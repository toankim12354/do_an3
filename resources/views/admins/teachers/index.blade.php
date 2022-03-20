@extends('layouts.app')

@section('title', __('Teacher'))

@section('name_page', 'Danh sách giảng viên')

@push('style')
<style>
	.utility-btn {
		border-radius: 5px;
	}
</style>
@endpush

@section('content')
<div>
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-header" data-background-color="blue">
		<strong style="font-size: 20px;">DANH SÁCH GIẢNG VIÊN</strong>
	</div>
	<div class="card-content">
		<div class="btn-group">
		  <a type="button" class="btn btn-info utility-btn" data-toggle="tooltip" data-placement="right" title="thêm giáo viên mới" href="{{ route('admin.teacher-manager.create') }}">
		  	THÊM
		  </a>

		  <a type="button" class="btn btn-success utility-btn" data-toggle="tooltip" data-placement="right" title="nhập danh sách giáo viên từ file excel" href="{{ route('admin.teacher-manager.form_import') }}">
		  	NHẬP
		  </a>

		  <a type="button" class="btn btn-warning utility-btn" data-toggle="tooltip" data-placement="right" title="xuất danh sách giáo viên ra file excel" href="{{ route('admin.teacher-manager.export_excel') }}">
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

			{{-- filter --}}
			<div class="col-lg-2 col-md-2 col-sm-2">
				<select class="selectpicker" data-style="select-with-transition" title="Giới tính" data-size="7" id="filterGender">
					<option value disabled> Giới tính</option>
					<option value="">Tất cả</option>
					<option value="1">Nam </option>
					<option value="0">Nữ</option>
				</select>
			</div>

			{{-- filter status --}}
			<div class="col-lg-2 col-md-2 col-sm-2">
				<select class="selectpicker" data-style="select-with-transition" title="Choose Status" data-size="7" id="filterStatus">
					<option value disabled> Choose Status</option>
					<option value="">Tất cả</option>
					<option value="1">Active</option>
					<option value="0">Inactive</option>
				</select>
			</div>

			{{-- search --}}
			<form class="navbar-form navbar-right" id="formSearch">
				<div class="form-group form-search is-empty">
					<input type="text" class="form-control" placeholder=" Tìm kiếm " name="search" value="" id="searchBar">
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
					<th>TÊN</th>
					<th>EMAIL </th>
					<th>ĐIỆN THOẠI </th>
					<th>NGÀY SINH </th>
					<th>ĐỊA CHỈ </th>
					<th>GIỚI TÍNH </th>
					<th>TRẠNG THÁI </th>
					<th class="text-right">HÀNH ĐỘNG </th>
				</tr>
			</thead>
			<tbody>
				@include('admins.teachers.load_index')
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

	function fetch_page(row = 10, gender, status, search, page = 1) {
		let url =  `{{ route('admin.teacher-manager.index') }}?row=${row}&gender=${gender}&status=${status}&search=${search}&page=${page}`;

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
			$('#filterGender').val(),
			$('#filterStatus').val(),
			$('#searchBar').val()
		];
	}
</script>
@endpush