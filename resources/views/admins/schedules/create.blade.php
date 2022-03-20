@extends('admins.schedules.layout.index')

@section('title', __('Schedules'))

@section('name_page', 'Thêm lịch học')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app/assigns/create.css') }}">
@endpush

@section('content')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-content">
				<form class="form-horizontal" id="form_assign">
					@csrf
					{{-- title --}}
					<input type="hidden" name="action" value="postAssign">
					<div class="card-header" data-background-color="blue">
						<h4 class="card-title">CHỌN PHÂN CÔNG</h4>
					</div>
					<div class="card-header">
						<div id="message_assign"></div>
					</div>
					<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
						<div class="col-md-4 form-group">
							<label>Grade</label>
							<select name="id_grade" title="Grade" class="select-grade select">
								<option disabled selected>
									Choose Grade
								</option>
								@foreach ($grades as $grade)
								<option value="{{ $grade->id }}">
									{{ $grade->name }}
								</option>
								@endforeach
							</select>
							<small class="show-error error-grade"></small>
						</div>
						<div class="col-md-4 form-group">
							<label>Subject</label>
							<select name="id_subject" title="Subject" class="select-subject select">
								<option disabled selected>
									Choose Subject
								</option>
							</select>
							<small class="show-error error-subject"></small>
						</div>
						<div class="col-md-4 form-group">
							<label>Teacher</label>
							<select name="id_teacher" title="Teacher" class="select-teacher select">
								<option disabled selected>
									Choose Teacher
								</option>
							</select>
							<small class="show-error error-teacher"></small>
						</div>
					</div>
					<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
						<div class="col-md-12 form-group">
							<button id="btnAssign" class="btn btn-success btn-round">Save</button>
						</div>
					</div>
				</form>
				<form class="form-horizontal" id="form" disabled="true" style="display: none;">
					@csrf
					<input type="hidden" name="id_assign" value="" id="id_assign">
					<div class="card-header" data-background-color="blue">
						<h4 class="card-title">THÊM LỊCH HỌC</h4>
					</div>
					{{-- alert --}}
					<div class="card-header">
						<div id="message"></div>
					</div>

					<div class="card-content">
						<div class="card-title">
							<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
								<div class="col-md-3 form-group"></div>
								<div class="col-md-6 form-group">
									<p><b>Grade</b>: <span id="grade"></span> - <b>Subject</b>: <span id="subject"></span> - <b>Teacher</b>: <span id="teacher"></span></p>
								</div>
								<div class="col-md-3 form-group" style="text-align:left;">
									<a href="#" id="edit_assign">Edit</a>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<th class="text-center" width="30%">CLASS ROOM</th>
										<th class="text-center" width="30%">DAY</th>
										<th class="text-center" width="30%">LESSON</th>
										<th class="text-center" width="10%">REMOVE</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="col-input">
											<select name="id_class_room[]" title="Class Room" class="select-classroom select">
												<option disabled selected>
													Choose Class Room
												</option>
												@foreach ($classrooms as $classroom)
												<option value="{{ $classroom->id }}">
													{{ $classroom->name }}
												</option>
												@endforeach
											</select>
											<small class="show-error error-classroom">
											</small>
										</td>
										<td class="col-input">
											<select name="day[]" title="Day" class="select-day select">
												<option disabled selected>
													Choose Day
												</option>
											</select>
											<small class="show-error error-day">
											</small>
										</td>
										<td class="col-input">
											<select name="id_lesson[]" title="Lesson" class="select-lesson select">
												<option disabled selected>
													Choose Lesson
												</option>
												@foreach ($lessons as $lesson)
												<option value="{{ $lesson->id }}">
													{{ $lesson->start . " - " . $lesson->end}}
												</option>
												@endforeach
											</select>
											<small class="show-error error-lesson">

											</small>
										</td>
										<td class="text-center">
											<i class="fas fa-times fa-2x remove-row" data-toggle="tooltip" title="remove this row"  data-placement="left"></i>
										</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="row text-center">
							<button type="button" class="btn btn-primary btn-round" id="addRow">NEW SCHEDULE</button>
							<button class="btn btn-success btn-round" id="btnSubmit">SAVE</button>
							<button type="reset" class="btn btn-warning btn-round">RESET</button>
							<button type="button" class="btn btn-danger btn-round" onclick="window.location.replace('{{ route('admin.schedule.index') }}')">back</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop

@push('script')
<script src="{{ asset('assets/js/helpers/array.js') }}"></script>
<script src="{{ asset('assets/js/helpers/selector.js') }}"></script>
<script src="{{ asset('assets/js/app/schedules/validation.js') }}"></script>
<script src="{{ asset('assets/js/app/assigns/validation.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();

		demo.initFormExtendedDatetimepickers();

		// submit form
		$(document).on('click', '#btnSubmit', function(e) {
			e.preventDefault();

			if (validationSchedule()) {
				submit();
			}
		});

		// add new row
		$(document).on('click', '#addRow', function() {
			let trLast = $('#form .table tbody tr:last');
			let trNew = trLast.clone();
			trNew.hide().insertAfter(trLast).fadeIn('slow');

			// clear error
			$('.show-error').html('').removeClass('error');
			$('[data-toggle="tooltip"]').tooltip();
		});

		// remove row
		$(document).on('click', '.remove-row', function() {
			let numberOfRow = $('tbody tr').length;

			if (numberOfRow > 1) {
				$(this).closest('tr').fadeOut('slow', function() {
					$(this).remove();
				});
			} else {
				$('#message').html('cannot remove this row').addClass('alert alert-danger');

				setTimeout(() => {
					$('#message').html('').removeClass('alert alert-danger');
				}, 5000);
			}
		});

		// clear error when select
		$(document).on('change', '.select', function() {
			$('.show-error').html('').removeClass('error');
		});
	});

	// submit function
	function submit() {
		$.ajax({
			url: '{{ route('admin.schedule.store') }}',
			type: 'POST',
			dataType: 'JSON',
			data: $('#form').serialize(),
			success: (res) => {
				window.location.replace(res.url);
			},
			error: (res) => {
				let errorRes = res.responseJSON;

				let allErrors = $('.show-error');
				let errorGrades = $('.error-classroom');
				let errorSubjects = $('.error-day');
				let errorTeachers = $('.error-lesson');

				$(allErrors).html('').removeClass('error');

				if (errorRes.code == 1) {
					let errorRows = errorRes.errorRows;
					let message = errorRes.message;

					for (let i of errorRows) {
						$(errorGrades[i]).html(message).addClass('error');
						$(errorSubjects[i]).html(message).addClass('error');
						$(errorTeachers[i]).html(message).addClass('error');
					}
				}

				if (errorRes.code == 2) {
					$('#message').html('').removeClass('alert alert-danger');

					let message = errorRes.message;

					$('#message').html(message).addClass('alert alert-danger');
				}
			}
		});
	}
	// update subject
	$(document).on('change', '.select-grade', function() {
		let token = $('#form_assign input[name = "_token"]').val();
		$.ajax({
			url: '{{ route('admin.schedule.requestAjax') }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				_token: token,
				id_grade: $(this).val(),
				action: 'updateSubject'
			},
			success: (res) => {
				let select_subject = $('.select-subject');
				let select_teacher = $('.select-teacher');
				let option_default = '<option disabled selected>Choose Subject</option>';
				$(select_subject[0]).html(option_default);
				$(res).each( function (index, value) {
					let option = $('<option>');
					$(option).val(value.id);
					$(option).text(value.name);
					$(select_subject[0]).append(option);
				});
				$(select_teacher[0]).html('<option disabled selected>Choose Teacher</option>')
			}
		});
	});

	// update teacher
	$(document).on('change', '.select-subject', function() {
		let token = $('#form_assign input[name = "_token"]').val();

		$.ajax({
			url: '{{ route('admin.schedule.requestAjax') }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				_token: token,
				id_subject: $(this).val(),
				id_grade: $($('.select-grade')[0]).val(),
				action: 'updateTeacher'
			},
			success: (res) => {
				let select_subject = $('.select-teacher');
				let option_default = '<option disabled selected>Choose Teacher</option>';
				$(select_subject[0]).html(option_default);
				$(res).each( function (index, value) {
					let option = $('<option>');
					$(option).val(value.id);
					$(option).text(value.name);
					$(select_subject[0]).append(option);
				});
			}
		});
	});

	// update days
	$(document).on('change', '.select-classroom', function() {
		let token = $('#form_assign input[name = "_token"]').val();
		$.ajax({
			url: '{{ route('admin.schedule.requestAjax') }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				_token: token,
				id_class_room: $(this).val(),
				action: 'updateDay'
			},
			success: (res) => {
				let tr = $(this).parent().parent();
				let day = $($(tr).children()[1]).children()[0];
				let lesson = $($(tr).children()[2]).children()[0];

				let option_default_day = '<option disabled selected>Choose Day</option>';
				let option_default_lesson = '<option disabled selected>Choose Lesson</option>';

				$(day).html(option_default_day);
				$(res.original).each( function (index, value) {
					let option = $('<option>');
					$(option).val(value);
					$(option).text("thứ "+(value+1));
					$(day).append(option);
				});
				$(lesson).html(option_default_lesson)
			}
		});
	});

	// update lesson
	$(document).on('change', '.select-day', function() {
		let token = $('#form_assign input[name = "_token"]').val();
		let tr = $(this).parent().parent();

		$.ajax({
			url: '{{ route('admin.schedule.requestAjax') }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
				_token: token,
				day: $(this).val(),
				id_class_room: $($($(tr).children()[0]).children()[0]).val(),
				action: 'updateLesson'
			},
			success: (res) => {
				let lesson = $($(tr).children()[2]).children()[0];
				let option_default = '<option disabled selected>Choose Lesson</option>';
				$(lesson).html(option_default);
				$(res.original).each( function (index, value) {
					let option = $('<option>');
					$(option).val(value.id);
					$(option).text(value.start+" - "+value.end);
					$(lesson).append(option);
				});
			}
		});
	});

	// submit assign
	$(document).on('click','#btnAssign', function (e) {
		e.preventDefault();
		if (validation()) {
			assignSubmit();
		}
	});
	//edit assign
	$('#edit_assign').on('click', function () {
		$('#form_assign').css('display', 'block');
		$('#form').css('display', 'none');
	});
	// assign submit
	function assignSubmit () {
		$.ajax({
			url: '{{ route('admin.schedule.requestAjax') }}',
			type: 'POST',
			dataType: 'JSON',
			data: $('#form_assign').serialize(),
			success: (res) => {
				if (res['status']) {
					$('#grade').text(res.grade);
					$('#subject').text(res.subject);
					$('#teacher').text(res.teacher);
					$('#id_assign').val(res.assign);
					$('#form_assign').css('display', 'none');
					$('#form').css('display', 'block');
				} else {
					$('#message_assign').text('Have not this assign').addClass('alert alert-danger');

					setTimeout(() => {
						$('#message_assign').text('').removeClass('alert alert-danger');
					}, 2000);
				}
			}
		});
	}
</script>
@endpush
