@extends('layouts.app')

@section('title', __('student'))

@section('name_page', 'Create student')

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<form method="post" action="
			{{ route('admin.student-manager.store') }}" class="form-horizontal">
				@csrf
				<div class="card-header card-header-text" data-background-color="rose">
					<h4 class="card-title">Thêm sinh viên</h4>
				</div>

				<div class="card-content">
					<div class="row">
						<label class="col-sm-2 label-on-right">Mã sinh viên </label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<input type="text" class="form-control" name="code" value="{{ old('code') }}">
								@error('code')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Lớp </label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<select name="id_grade" id="" class="selectpicker" data-style="select-with-transition" title="Chọn lớp">
									<option value="" disabled>Chọn lớp</option>
									@foreach ($grades as $grade)
										<option value="{{ $grade->id }}" 
											{{ 
												$grade->id == old('id_grade') 
												? 'selected' : '' 
											}}
										>
											{{ $grade->name }}
										</option>
									@endforeach
								</select>
								@error('id_grade')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Họ và tên </label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<input type="text" class="form-control" name="name" value="{{ old('name') }}">
								@error('name')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Email</label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
								@error('email')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Điện thoại </label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<input type="number" class="form-control" name="phone" value="{{ old('phone') }}">
								@error('phone')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Địa chỉ </label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<input type="text" class="form-control" name="address" value="{{ old('address') }}">
								@error('address')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Ngày sinh </label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<input type="text" class="form-control datepicker" name="dob" value="{{ old('dob') }}" />
								@error('dob')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Giới tính </label>
						<div class="col-sm-10 checkbox-radios">
							<div class="radio">
								<label>
									<input type="radio" name="gender" value="1"
									{{ old('gender') == '1' ?'checked' : '' }}> Nam 
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="gender" value="0"
									{{ old('gender') == '0' ?'checked' : '' }}> Nữ 
								</label>
							</div>
							@error('gender')
								<div class="alert alert-danger">
									{{ $message }}
								</div>
							@enderror
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-center">
							<button class="btn btn-success btn-round">LƯU </button>
							<button type="reset" class="btn btn-warning btn-round">KHÔI PHỤC </button>
							<button type="button" class="btn btn-danger btn-round" onclick="window.location.replace('{{ route('admin.student-manager.index') }}')">QUAY LẠI </button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@stop
@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        demo.initFormExtendedDatetimepickers();
    });
</script>
@endpush