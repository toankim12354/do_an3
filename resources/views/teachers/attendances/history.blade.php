@extends('layouts.app')

@section('title', __('Teacher'))

@section('name_page', 'Xem điểm danh')

@section('content')
<div>
	{{ Breadcrumbs::render() }}
</div>
<div class="card">
	<div class="card-header main-title text-center" data-background-color="green">
		<h3>LỊCH SỬ ĐIỂM DANH</h3>
	</div>
	<div class="card-content">
		<div class="container-fluid">
			<div class="row card" style="display: flex; justify-content: center; padding: 10px;">
				<div class="form-group col-md-4">
					<select name="" id="grade" style="width: 100%; padding: 5px;">
						<option selected disabled>Choose Grade</option>
						@foreach ($grades as $grade)
						<option value="{{ $grade->id }}">
							{{ $grade->name }}
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

			<div class="row">
				<ul>
					<li><span class="text-success">ĐH</span>: đi học</li>
					<li><span class="text-warning">M</span>: muộn </li>
					<li><span class="text-danger">N</span>: nghỉ </li>
					<li><span class="text-info">CP</span>: có phép </li>
					<li><span class="">CN</span>: chưa điểm danh</li>
				</ul>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 text-center" id="updatedAt"></div>
			</div>
		</div>
		<hr style="height: 2px; background: grey;">
		<div class="table-responsive card" id="tableHistoryBox" style="overflow-x: scroll;">
			
		</div>
		<div class="text-center">
			<button class="btn btn-success btn-round" id="saveBtn">LƯU</button>
		</div>
	</div>
</div>
@stop
@push('script')
<script type="text/javascript">
	$(function() {
		let app = (function() {
			let grade           = $('#grade');
			let subject         = $('#subject');
			let tableHistoryBox = $('#tableHistoryBox');
			let saveBtn 		= $('#saveBtn');

			return {
				// get subject
				getSubject(idGrade) {
					let data = {id_grade: idGrade, action: 'get_subject'};

					$.ajax({
						url: `{{ route("teacher.attendance.history") }}`,
						type: 'GET',
						data: data,
						dataType: 'json',
						success: (res) => {
							let subjects = res.subjects;
							render_option({
								select: '#subject',
								field: {valueField: 'id', textField: 'name'},
								type: 'fresh',
								data: subjects,
								default: {
									attr: "disabled selected", 
									text: 'Choose Subject'
								}
							});
						},
						error: (res) => {
							console.log(res);
							window.location.href = res.responseJSON.url;
						}
					});
				},

				// get history
				getHistory(idGrade, idSubject) {
					let data = {
						id_grade: idGrade, 
						id_subject: idSubject, 
						action: 'get_attendance_history'
					};

					$.ajax({
						url: `{{ route("teacher.attendance.history") }}`,
						data: data,
						dataType: 'json',
						success: (res) => {
							console.log(res);
							tableHistoryBox.html(res.html);
							saveBtn.show();
						},
						error: (res) => {
							let error = res.responseJSON;
							console.log(error);

							if ('message' in error) {
								alert(error.message);
							}

							// window.location.href = res.responseJSON.url;
						}
					});
				},

				// make data
				makeData() {
					let dataElements = $('.data');
					let record = [];

					$(dataElements).each(function() {
						let idAttendance = $(this).data('id-attendance');
						let idStudent    = $(this).data('id-student');
						let status = $(this).val();

						if (status !== '') {
							let row = {
								id_attendance: idAttendance, 
								id_student: idStudent,
								status: status
							}

							record.push(row);
						}
					});

					let data = {
						_token: `{{ csrf_token() }}`,
						id_grade: $('#grade').val(),
						id_subject: $('#subject').val(),
						record: JSON.stringify(record)
					};

					return data;
				},

				// update
				update(data) {
					$.ajax({
						url: `{{ route("teacher.attendance.update_history") }}`,
						type: 'POST',
						data: data,
						dataType: 'json',
						success: (res) => {
							console.log(res);
							$('#updatedAt').html(`
								<strong class="text-info">Cập nhật lúc: 
								<span class="text-danger">${res.updatedAt}</span>
								</strong>`);

							alert('update success');

							// fetch new data
							this.getHistory(grade.val(), subject.val());
						},
						error: (res) => {
							let error = res.responseJSON;

							if ('url' in error) {
								window.location.href = error.url;
							}
						}
					})
				},

				// run
				run() {
					grade.find('option:disabled').prop('selected', true);
					// an nut save khi chua chon lop + mon
					saveBtn.hide();

					// get subject
					$(document).on('change', '#grade', () => {
						this.getSubject(grade.val());
						subject.prop('disabled', false);
					});

					// get history
					$(document).on('change', '#subject', () => {
						this.getHistory(grade.val(), subject.val());
					});

					// update
					$(document).on('click', '#saveBtn', () => {
						let data = this.makeData();
						console.log(data);
						this.update(data);
					});
				}
			}
		})();

		app.run();
	});
</script>
@endpush