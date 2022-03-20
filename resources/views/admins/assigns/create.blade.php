@extends('layouts.app')

@section('title', __('Assign'))

@section('name_page', 'Thêm phân công')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app/assigns/create.css') }}">
@endpush

@section('content')
<div>
	{{ Breadcrumbs::render() }}
</div>
<div class="row">

	<div class="col-md-12">
		<div class="card">
			<form class="form-horizontal" id="form">
				@csrf

				{{-- title --}}
				<div class="card-header" data-background-color="blue">
					<h4 class="card-title">THÊM PHÂN CÔNG</h4>
				</div>

				{{-- alert --}}
				<div class="card-header">
					<div id="message"></div>
				</div>

				<div class="card-content">
					<div class="table-responsive">
						<table class="table table-hover table-bordered">
							<thead>
								<tr>
									<th class="text-center" width="23%">GRADE</th>
									<th class="text-center" width="23%">SUBJECT</th>
									<th class="text-center" width="23%">TEACHER</th>
									<th class="text-center" width="23%">START</th>
									<th class="text-center" width="8%">REMOVE</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="col-input">
										<select name="id_grade[]" title="Grade" class="select-grade select">
											<option disabled selected>
												Choose Grade
											</option>
											@foreach ($grades as $grade)
											<option value="{{ $grade->id }}">
												{{ $grade->name }}
											</option>
											@endforeach
										</select>
										<small class="show-error error-grade">
										</small>
									</td>
									<td class="col-input">
										<select name="id_subject[]" title="Subject" class="select-subject select">
											<option disabled selected>
												Choose Subject
											</option>
											@foreach ($subjects as $subject)
											<option value="{{ $subject->id }}">
												{{ $subject->name }}
											</option>
											@endforeach
										</select>
										<small class="show-error error-subject">
										</small>
									</td>
									<td class="col-input">
										<select name="id_teacher[]" title="Teacher" class="select-teacher select">
											<option disabled selected>
												Choose Teacher
											</option>
											@foreach ($teachers as $teacher)
											<option value="{{ $teacher->id }}">
												{{ $teacher->name }}
											</option>
											@endforeach
										</select>
										<small class="show-error error-teacher">

										</small>
									</td>

									<td class="col-input">
										<div class="form-group label-floating is-empty">
											<input type="text" class="select select-start" name="start_at[]"/>
										</div>
										<small class="show-error error-start">

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
						<button type="button" class="btn btn-primary btn-round" id="addRow">NEW ASSIGN</button>
						<button class="btn btn-success btn-round" id="btnSubmit">SAVE</button>
						<button type="reset" class="btn btn-warning btn-round">RESET</button>
						<button type="button" class="btn btn-danger btn-round" onclick="window.location.replace('{{ route('admin.assign.index') }}')">back</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@stop

@push('script')
<script src="{{ asset('assets/js/helpers/array.js') }}"></script>
<script src="{{ asset('assets/js/helpers/selector.js') }}"></script>
<script src="{{ asset('assets/js/app/assigns/validation.js') }}"></script>
<script src="{{ asset('assets/js/my-datepicker.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		const app = (function() {
			const form = $('#form');

			return {
				// add new row
				addRow() {
					let trLast = $('#form .table tbody tr:last');
					let trNew = trLast.clone();
					$(trNew).find('input')
							.removeAttr('id')
							.removeClass('hasDatepicker')
							.val('');

					// effect fade in when add new row
					trNew.hide().insertAfter(trLast).fadeIn('slow');

					// clear error when add new row
					$('.show-error').html('').removeClass('error');

					// set tooltip after add new row
					$('[data-toggle="tooltip"]').tooltip();

					// set date picker for input field after add new row
					my_datepicker('.select-start');
				},

				// remove row
				removeRow() {
					let numberOfRow = $('#form .table tbody tr').length;
					
					if (numberOfRow > 1) {
						$(this).closest('tr').fadeOut('slow', function() {
							$(this).remove();
						});
					} else {
						render_alert('error', 'cannot remove this row', '#message');
					}
				},

				// save
				save() {
					$.ajax({
						url: '{{ route('admin.assign.store') }}',
						type: 'POST',
						dataType: 'JSON',
						data: $('#form').serialize(),
						success: (res) => {
							window.location.replace(res.url);
						},
						error: (res) => {
							let error = res.responseJSON;

							if ('url' in error) {
								window.location.replace(error.url);
							}

							if ('message' in error && 'rowErrors' in error) {
								let element = [
										'.error-grade', 
										'.error-subject', 
										'.error-teacher'
									];

								// clear error before display new error
								clear_error(element, 'error');

								let renderErrorOption = [
									{
										element: element, 
										index: error.rowErrors, 
										message: error.message,
										class: 'error'
									}
								];

								render_error(renderErrorOption);
							}
						}
					});
				},

				// run
				run() {
					// set datepicker and tooltip
					my_datepicker('.select-start');
					$('[data-toggle="tooltip"]').tooltip();

					// add row
					$(document).on('click', '#addRow', this.addRow);

					// remove row
					$(document).on('click', '.remove-row', this.removeRow);

					// save
					$(document).on('click', '#btnSubmit', (e) => {
						e.preventDefault();

						if (validation()) {
							this.save();
						}
					});
				}
			}
		})();

		app.run();
	});

</script>
@endpush