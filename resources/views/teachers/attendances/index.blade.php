@extends('layouts.app')

@section('title', __('Teacher'))

@section('name_page', 'Attendance')

@section('content')

@push('style')
	<style>
		#boxInfo .card {
			max-height: 100px;
		}

		#boxInfo .card .header-card-info {
			padding: 8px 2px;
			border-radius: 6px;
			color: white;
			font-weight: bold;
			box-shadow: rgba(0, 0, 0, 0.35) 0px -50px 36px -28px inset;
		}

		.main-title {
			/*height: 50px;*/
			padding: 5px 10px !important;
		}

		.main-title h3 {
			margin: 0;
		}
	</style>
@endpush

<div class="card">
	<div class="card-header main-title text-center" data-background-color="green">
		<h3>ĐIỂM DANH</h3>
		</div>
	<div class="card-content">
		<div class="container-fluid">
			<div class="row card" style="display: flex; justify-content: center; padding: 10px;">
				<div class="form-group col-md-4">
					<select name="" id="grade" style="width: 100%; padding: 5px;">
						<option selected disabled>Choose Grade</option>
						@foreach ($grades as $grade)
						<option value="{{ $grade->id }}">
							{{ $grade->name . $grade->yearSchool->name }}
						</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-md-4">
					<select name="" id="subject" style="width: 100%; padding: 5px;" disabled>
						<option selected disabled>Choose Subject</option>
					</select>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12" id="alert"></div>
			</div>

			<div class="row" id="boxInfo">
				<div class="col-md-3">
					<div class="card card-stats">
						<div class="header-card-info text-center" style="background: skyblue;">
							<span >Thời lượng môn học</span>
						</div>
						<div class="card-content">
							<h3 class="card-title text-center" id="subjectDuration"></h3>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card card-stats">
						<div class="header-card-info text-center" style="background: darkorange;">
							<span >Số giờ đã dạy các tháng trước</span>
						</div>
						<div class="card-content">
							<h3 class="card-title text-center" id="timeDonePreviousMonths"></h3>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card card-stats">
						<div class="header-card-info text-center" style="background: yellowgreen;">
							<span >Số giờ đã dạy tháng này</span>
						</div>
						<div class="card-content">
							<h3 class="card-title text-center" id="timeDoneCurrentMonth"></h3>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card card-stats">
						<div class="header-card-info text-center" style="background: lightseagreen;">
							<span >Số giờ còn lại</span>
						</div>
						<div class="card-content">
							<h3 class="card-title text-center" id="timeRemain"></h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr style="height: 2px; background: grey;">
		<div class="table-responsive card">
			<table class="table table-striped" id="tableAttendance">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Name</th>
						<th>Status</th>
						<th>Note</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop
@push('script')
<script type="text/javascript">
	$(function() {
		const app = (function() {
			let table = $('#tableAttendance');
			let grade = $('#grade');
			let subject = $('#subject');
			let boxInfo = $('#boxInfo');

			return {
				// get subject
				getSubject(idGrade) {
					$.ajax({
						url: '{{ route('teacher.attendance.create') }}',
						type: 'GET',
						dataType: 'json',
						data: {id_grade: idGrade, action: 'get_subject'},
						success: (res) => {
							let subjects = res.subjects;
							render_option({
								select: '#subject',
								field: {valueField: 'id', textField: 'name'},
								type: 'fresh',
								data: subjects,
								default: {attr: "disabled selected", text: 'Choose Subject'}
							});
						},
						error: (res) => {
							let error = res.responseJSON;

							// redirect to 404 page if not found action
							if (error.hasOwnProperty('url')) {
								window.location.replace(error.url);
							}
						}
					});
				},

				// get data for create or edit
				getData(idGrade, idSubject) {
					$.ajax({
						url: '{{ route('teacher.attendance.create') }}',
						type: 'GET',
						dataType: 'json',
						data: {
							id_grade: idGrade,
							id_subject: idSubject, 
							// current_date: '18-08-2021',
							action: 'get_data'
						},
						success: (res) => {
							console.table(res);
							this.renderResponse(res);
							table.show();
							boxInfo.show();

							// init ckeditor
							CKEDITOR.replace('mainNote');

							// display value get from server
							CKEDITOR.instances.mainNote.setData($('#mainNote').data('value'));
						},
						error: (res) => {
							let error = res.responseJSON;
							console.log(error);
							table.hide();
							boxInfo.hide();

							render_alert('warning', error.message, '#alert', 5000);
							table.hide();
						}
					});
				},

				// make data for submit
				makeData() {
					let data = {
						_token: `{{ csrf_token() }}`,
						id_grade: grade.val(),
						id_subject: subject.val(),
						main_note: CKEDITOR.instances.mainNote.getData(), 
						// current_date: '18-08-2021',
						record: []
					};

					$('.id').each(function(k, v) {
						let id = $(v).val();
						let status = $(`.status_${id}:checked`).val();
						let subNote = $(`#subNote_${id}`).val();

						let row = {
							id_student: id, 
							status: status, 
							note: subNote
						};

						data.record.push(row);
					});

					return data;
				},

				// save
				save(data) {
					$.ajax({
						url: '{{ route('teacher.attendance.store') }}',
						data: data,
						type: 'POST',
						dataType: 'json',
						success: (res) => {
							console.table(res);
							this.renderResponse(res);
							alert("cap nhat thanh cong");
						},
						error: (res) => {
							let error = res.responseJSON;
							console.log(res, error);

							if ('url' in error) {
								window.location.href = `${error.url}`;
							}

							if ('message' in error) {

							}
						}
					});
				},

				// render response
				renderResponse(res) {

					// INFO ASIGN AND ATTENDANCE
					if ('subjectDuration' in res) {
						$('#subjectDuration').html(
							`${res.subjectDuration} giờ`
						);
					}

					if ('timeDonePreviousMonths' in res) {
						$('#timeDonePreviousMonths').html(
							`${res.timeDonePreviousMonths} giờ`
						);
					}
					
					if ('timeDoneCurrentMonth' in res) {
						$('#timeDoneCurrentMonth').html(
							`${res.timeDoneCurrentMonth} giờ`
						);
					}

					if ('timeRemain' in res) {
						$('#timeRemain').html(`${res.timeRemain} giờ`);
					}

					if ('updateAt' in res) {
						$('#updateAt').html(`
							<strong class="text-info">Cập nhật lúc: 
							<span class="text-danger">${res.updateAt}</span>
							</strong>`
						);
					}

					// DATA FOR ATTENDANCE (LIST STUDENT AND THEIR STATUS)
					if ('html' in res) {
						$('#tableAttendance tbody').html(res.html);
					}
				},

				// run
				run() {
					grade.find('option:disabled').prop('selected', true);
					table.hide();
					boxInfo.hide();

					// get subject when choose grade
					$(document).on('change', '#grade', () => {
						this.getSubject(grade.val());
						subject.prop('disabled', false);
					});

					// get data
					$(document).on('change', '#subject', () => {
						this.getData(grade.val(), subject.val());
					})

					// save
					$(document).on('click', '#saveBtn', () => {
						let data = this.makeData();
						console.log(data);
						this.save(data);
					});
				}
			}
		})();

		app.run();
	});
</script>
@endpush