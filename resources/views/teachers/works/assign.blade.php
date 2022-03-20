@extends('layouts.app')

@section('title', __('teacher'))

@section('name_page', 'Quản lý dạy')

@section('content')
<div>
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-header card-header-icon" data-background-color="rose">
		<i class="material-icons">assignment</i>
	</div>
	<div class="card-content">
		<div class="card-title" style="display: flex; justify-content: flex-end; align-items: center;">
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
		</div>
		<div class="table-responsive">
			<table class="table table-striped" id="myAssignTable">
				<thead>
					<tr>
						<th></th>
						<th>Lớp</th>
						<th>Số giờ định mức</th>
						<th>Số giờ đã dạy các tháng trước</th>
						<th>Số giờ đã dạy tháng này</th>
						<th>Tình trạng</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($data as $groupAssign)
					<tr>
						<td colspan="6" class="bg-info">{{ $groupAssign['subject']->name }}</td>
					</tr>
					@foreach ($groupAssign['assignsOfSubject'] as $assign)
					<tr class="td-content">
						<td>{{ $assign->subject->name }}</td>
						<td>{{ $assign->grade->name }}</td>
						<td>{{ $assign->subject->duration }}</td>
						<td>{{ $assign->info['timeDonePreviousMonths'] }}</td>
						<td>{{ $assign->info['timeDoneCurrentMonth'] }}</td>
						<td>
							@if ($assign->status == '1' && count($assign->schedules) > 0)
							<span class="badge" style="background: green;">Đang dạy</span>
							@elseif($assign->status == '1' && count($assign->schedules) === 0)
							<span class="badge" style="background: orange;">Chưa dạy</span>
							@elseif($assign->status == '2')
							<span class="badge" style="background: red;">Đã dạy xong</span>
							@endif
						</td>
					</tr>
					@endforeach
					@endforeach
				</tbody>
			</table>

		</div>
	</div>
</div>
@stop

@push('script')
<script type="text/javascript">
	$(function() {
		$(document).on('keyup', '#searchBar', function() {
			let value = $(this).val().toLowerCase();
			$("#myAssignTable .td-content").filter(function() {
				let status = $(this).text().toLowerCase().indexOf(value) > -1;
				$(this).toggle(status);
			});
		});
	});
</script>
@endpush
