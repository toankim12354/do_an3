@extends('layouts.app')

@section('title', __('Teacher'))

@section('name_page', 'Thông tin giảng viên')

@section('content')
<div>
	{{ Breadcrumbs::render() }}
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<form method="post" action="
			{{ route('admin.teacher-manager.update', $teacher->id) }}" class="form-horizontal">
				@csrf
				@method('put')

				{{-- id for FormRequest unique ignore --}}
				<input type="hidden" name="id" value="{{ $teacher->id }}">
				<div class="card-header card-header-text" data-background-color="blue">
					<h4 class="card-title">Thông tin giảng viên</h4>
				</div>

				<div class="card-content">
					<div class="card-title">
						{{-- alert error --}}
						@if (session('error'))
						<div class="alert alert-dismissable alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 20px;">
								<span aria-hidden="true">&times;</span>
							</button>
							<strong>Error!</strong> {{ session('error') }}
						</div>
						@endif
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Họ và tên</label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<input type="text" class="form-control" name="name" value="{{ old('name', $teacher->name) }}">
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
								<input type="email" class="form-control" name="email" value="{{ old('email', $teacher->email) }}">
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
								<input type="number" class="form-control" name="phone" value="{{ old('phone', $teacher->phone) }}">
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
								<input type="text" class="form-control" name="address" value="{{ old('address', $teacher->address) }}">
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
								<input type="text" class="form-control datepicker" name="dob" value="{{ old('dob', $teacher->dob) }}" />
								@error('dob')
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
									@if (old('gender') !== null))
										{{ old('gender') == '1' 
											? 'checked' : '' }}
									@else
										{{ $teacher->gender == 'Nam' 
											? 'checked' : '' }}
									@endif> Nam
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="gender" value="0"
									@if (old('gender') !== null))
										{{ old('gender') == '0' 
											? 'checked' : '' }}
									@else
										{{ $teacher->gender == 'Nữ' 
											? 'checked' : '' }}
									@endif> Nữ
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
						<label class="col-sm-2 label-on-right">Trạng thái</label>
						<div class="col-sm-2">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<select name="status" id="" class="selectpicker" data-style="select-with-transition" title="Chọn trạng thái">
									<option value="" disabled>Chọn trạng thái</option>
									<option value="1"
										@if (old('status') !== null))
											{{ old('status') == '1' 
												? 'selected' : '' }}
										@else
											{{ $teacher->status == '1' 
												? 'selected' : '' }}
										@endif
									>
										Active
									</option>
									<option value="0"
										@if (old('status') !== null))
											{{ old('status') == '0' 
												? 'selected' : '' }}
										@else
											{{ $teacher->status == '0' 
												? 'selected' : '' }}
										@endif
									>
										Inactive
									</option>
								</select>
								@error('status')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
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