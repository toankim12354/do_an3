@extends('layouts.app')

@section('title', __('Teacher'))

@section('name_page', 'Thêm giảng viên')

@section('content')
<div>
	{{ Breadcrumbs::render() }}
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<form method="post" action="
			{{ route('admin.teacher-manager.store') }}" class="form-horizontal">
				@csrf
				<div class="card-header card-header-text" data-background-color="rose">
					<h4 class="card-title">Thêm giảng viên</h4>
				</div>

				<div class="card-content">
					<div class="row">
						<label class="col-sm-2 label-on-right">Họ và tên</label>
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
						<label class="col-sm-2 label-on-right">Điện thoại</label>
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
						<label class="col-sm-2 label-on-right">Địa chỉ</label>
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
						<label class="col-sm-2 label-on-right">Ngày sinh</label>
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
						<label class="col-sm-2 label-on-right">Mật khẩu</label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<input type="password" class="form-control" name="password" value="{{ old('password') }}">
								@error('password')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Giới tính</label>
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
							<button class="btn btn-success btn-round">LƯU</button>
							<button type="reset" class="btn btn-warning btn-round">KHÔI PHỤC</button>
							<button type="button" class="btn btn-danger btn-round" onclick="window.location.replace('{{ route('admin.teacher-manager.index') }}')">QUAY LẠI</button>
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