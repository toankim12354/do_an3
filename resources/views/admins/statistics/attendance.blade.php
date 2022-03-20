@extends('layouts.app')

@section('title', __('statistic attendance'))

@section('name_page', 'statistic attendance')

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-content">
		@if (session('success'))
		<div class="alert alert-dismissable alert-success">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 20px;">
				<span aria-hidden="true">&times;</span>
			</button>
			<strong>Success!</strong> {{ session('success') }}
		</div>
		@endif
		<div class="container-fluid">
			<form action="{{ route('admin.statistic.export') }}" method="POST" id="formStatistic">
				@csrf
				<div class="row" style="display: flex; justify-content: center; padding: 10px; align-items: center;">
					<div class="form-group col-md-4">
						<select name="id_grade" id="grade" style="width: 100%; padding: 5px;" class="selectpicker">
							<option selected disabled>Chọn lớp</option>
							@foreach ($grades as $grade)
							<option value="{{ $grade->id }}">
								{{ $grade->name }}
							</option>
							@endforeach
						</select>

						@error('id_grade')
							<span>{{ $message }}</span>
						@enderror
					</div>
					<div class="form-group col-md-4">
						<select name="id_subject[]" id="subject" style="width: 100%; padding: 5px;" class="selectpicker" disabled multiple>
							<option selected disabled>Chọn môn học</option>
						</select>

						@error('id_subject')
							<span>{{ $message }}</span>
						@enderror
					</div>
					<div class="form-group col-md-2">
						<button class="btn btn-success btn-round" style="margin-top: 20px !important;" id="btnExportExcel">Export</button>
					</div>
					<div class="form-group col-md-2">
						<button class="btn btn-info btn-round" style="margin-top: 20px !important;" id="btnSendEmail">GỬI EMAIL </button>
					</div>
				</div>
			</form>
		</div>
		<div class="table-responsive" id="statisticContent">
			
		</div>
	</div>
</div>
@stop
@push('script')
<script type="text/javascript">
	$(function() {
		let app = (function() {
			let grade = $('#grade');
			let subject = $('#subject');
			let btnExportExcel = $('#btnExportExcel');

			return {
				getSubject(idGrade) {
					let data = {id_grade: idGrade, action: "get_subject"};

					$.ajax({
						url: `{{ route('admin.statistic.attendance') }}`,
						type: 'GET',
						dataType: 'json',
						data: data,
						success: (res) => {
							console.log(res);
							let subjects = res.subjects;
							render_option({
								select: '#subject',
								field: {valueField: 'id', textField: 'name'},
								type: 'fresh',
								data: subjects,
								default: {attr: "disabled", text: 'Chọn môn học '}
							});
						},
						error: (res) => {
							console.log(res);
						}
					});
				},

				getStatistic(idGrade, idSubject) {
					let data = {
						id_grade: idGrade,
						id_subject: idSubject,
						action: 'get_statistic_attendance'
					};

					$.ajax({
						url: `{{ route('admin.statistic.attendance') }}`,
						type: 'GET',
						dataType: 'json',
						data: data,
						success: (res) => {
							$('#statisticContent').html(res.html);
						},
						error: (res) => {
							console.log(res);
						}
					});
				},

				run() {
					grade.find('option:disabled').prop('selected', true);
					
					$(document).on('change', '#grade', (e) => {
						e.preventDefault();
						this.getSubject(grade.val());
						subject.prop('disabled', false);
					});

					$(document).on('change', '#subject', (e) => {
						e.preventDefault();
						$('#statisticContent').html('');
						this.getStatistic(grade.val(), subject.val());
					});

					$(document).on('click', '#btnExportExcel', function(e) {
						e.preventDefault();
						$('#formStatistic').attr('action', 
							`{{ route('admin.statistic.export') }}`);
						$('#formStatistic').submit();
					});

					$(document).on('click', '#btnSendEmail', function(e) {
						e.preventDefault();
						$(this).prop('disabled', true);
						$('#formStatistic').attr('action', 
							`{{ route('admin.statistic.send_email') }}`);
						$('#formStatistic').submit();
					});
				}
			}
		})();

		app.run();
	});
</script>
@endpush