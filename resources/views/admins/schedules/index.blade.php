@extends('admins.schedules.layout.index')

@section('title', __('schedules'))

@section('name_page', 'Schedule Management')


@section('content.schedules')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card-title" style="display: flex; justify-content: space-between; align-items: center;">
	<a href="{{ route('admin.schedule.create') }}">
		<button class="btn btn-success btn-round"
		data-toggle="tooltip" title="Add New Schedule" data-placement="left" style="padding-left: 14px; padding-right: 14px;">
			<i class="fas fa-plus fa-lg"></i>
		</button>
	</a>
</div>
<div class="table-responsive">
	<table width="100%" style="text-align: center;">
		<tr>
			<td style="width: 33.33%">
				<div style="width: 80%; height: 200px; background-image: url({{ asset('assets/img/schedule_class.jpg') }});">
					<a href="{{ route('admin.schedule.indexClass') }}">
						<button class="btn btn-warning btn-round"
						data-toggle="tooltip" title="Add New Schedule" data-placement="left" style="padding-left: 14px; padding-right: 14px;">
							Quản lý theo lớp
						</button>
					</a>
				</div>
			</td>
			<td style="width: 33.33%">
				<div style="width: 80%; height: 200px; background-image: url({{ asset('assets/img/schedule_teacher_2.jpg') }});">
					<a href="{{ route('admin.schedule.indexTeacher') }}">
						<button class="btn btn-primary btn-round"
						data-toggle="tooltip" title="Add New Schedule" data-placement="left" style="padding-left: 14px; padding-right: 14px;">
							Quản lý theo giáo viên
						</button>
					</a>
				</div>
			</td>
			<td style="width: 33.33%">
				<div style="width: 80%; height: 200px; background-image: url({{ asset('assets/img/schedule_all.jpg') }});">
					<a href="{{ route('admin.schedule.indexAll') }}">
						<button class="btn btn-default btn-round"
						data-toggle="tooltip" title="Add New Schedule" data-placement="left" style="padding-left: 14px; padding-right: 14px;">
							Quản lý tất cả
						</button>
					</a>
				</div>
			</td>
	</table>
</div>
@stop
