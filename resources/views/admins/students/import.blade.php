@extends('layouts.app')

@section('title', __('assign'))

@section('name_page', 'List assign')

@push('style')
<style>
.file-input::file-selector-button {
	background-color: orange;
	color: white;
	width: 100px;
	height: 40px;
	border-radius: 5px;
	border: none;
	box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
}

.file-input::file-selector-button:hover {
	cursor: pointer;
}

.file-input {
	border: 1px solid grey;
	border-radius: 5px;
	width: 100%;
}

.wrapper-input {
	padding: 5px;
}

#btnImport {

}
</style>
@endpush

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="row" style="display: flex; justify-content: center;">
	<div class="col-md-10">
		<div class="card">
			<div class="card-header">
				<h2 class="text-center">NHẬP DANH SÁCH SINH VIÊN</h2>
				<h6 class=""><i class="fas fa-exclamation-triangle text-warning"></i>Chỉ chọn file excel</h6 class="text-warning">
				<h6 class=""><i class="fas fa-exclamation-triangle text-warning"></i>Các trường của file:</h6 class="text-warning">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Mã sinh viên </th>
							<th>Họ tên </th>
							<th>Ngày sinh </th>
							<th>Email </th>
							<th>Điện thoại </th>
							<th>Địa chỉ </th>
							<th>Giới tính </th>
							<th>Lớp </th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
			@if (session('success'))
			<div class="card-header">
				<div class="alert alert-dismissable alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 20px;">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success!</strong> {{ session('success') }}
				</div>
			</div>
			@endif

			@if (session('error'))
			<div class="card-header">
				<div class="alert alert-dismissable alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 20px;">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success!</strong> {{ session('error') }}
				</div>
			</div>
			@endif

			@if (session('failures'))
			{{-- {{ dd(session('failures')) }} --}}
			<div class="card-header table-responsive">
				<h5 class="text-center text-warning">
					<i class="fas fa-exclamation-triangle"></i>
					Các hàng lỗi sẽ bị bỏ qua trong quá trình nhập
				</h5>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Hàng</th>
							<th>Cột</th>
							<th>Lỗi</th>
							<th>Giá trị lỗi</th>
						</tr>
					</thead>
					<tbody class="text-danger">
						@foreach (session('failures') as $failure)
						<tr>
							<th>{{ $failure->row() }}</th>
							<th>{{ $failure->attribute() }}</th>
							<th>
								<ul>
									@foreach ($failure->errors() as $error)
									<li>{{ $error }}</li>
									@endforeach
								</ul>
							</th>
							<th>{{ $failure->values()[$failure->attribute()] ?? '' }}</th>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@endif
			<div class="card-content">
				<form action="{{ route('admin.student-manager.import_excel') }}" method="POST" enctype="multipart/form-data">
					@csrf

					<div class="wrapper-input">
						<input type="file" name="file" class="file-input">
						@error('file')
						<div>
							<span class="text-danger">{{ $message }}</span>
						</div>
						@enderror
					</div>

					<div class="wrapper-input text-center">
						<button class="btn btn-success btn-round" id="btnImport">
							Import
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop
@push('script')
<script type="text/javascript">
	$(document).ready(function() {
		
	});
</script>
@endpush