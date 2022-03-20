@extends('admins.schedules.layout.index')

@section('title', __('schedules'))

@section('name_page', 'Schedule For Teacher')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app/assigns/create.css') }}">
@endpush

@section('content.schedules')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<form class="form-horizontal" id="form_teacher" style="margin-top: 60px;">
	@csrf
	{{-- title --}}
	<div class="card-header" data-background-color="blue">
		<h4 class="card-title">CHOOSE TEACHER</h4>
	</div>
	<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
		<div class="col-md-4 form-group">
			<label>Teacher</label>
			<select name="id_teacher" title="Teacher" class="select-teacher select">
				<option disabled selected>
					Choose Teacher
				</option>
				@foreach ($teachers as $valueTeacher)
				<option value="{{ $valueTeacher->id }}">
					{{ $valueTeacher->name }}
				</option>
				@endforeach
			</select>
			<small class="show-error error-teacher"></small>
		</div>
	</div>
	<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
		<div class="col-md-12 form-group">
			<button id="btnTeacher" class="btn btn-success btn-round">Save</button>
		</div>
	</div>
</form>
<div id="select_teacher" style="display: none;">
    <div style="margin-bottom: 100px;">
        <div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
            <div class="col-md-4 form-group">
                <select class="input form-control" id="teacher">
                    @foreach ($teachers as $valueTeacher)
                        <option value="{{ $valueTeacher->id }}">
                            {{ $valueTeacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div id="schedule_of_teacher" style="display: none;">
{{--     @include('admins.schedules.schedules_teacher')--}}
</div>
@stop

@push('script')
<script src="{{ asset('assets/js/helpers/array.js') }}"></script>
<script src="{{ asset('assets/js/helpers/selector.js') }}"></script>
<script type="text/javascript">
    // multiple export
    $(document).on('click', '#exort_multiple', function (e) {
        let id_assign = $('#assigns').val();
        if (id_assign.length === 0) {
            e.preventDefault();
            alert("please choose assign");
            return false;
        }
    });

    // submit assign
	$(document).on('click','#btnTeacher', function (e) {
		e.preventDefault();
		let id_teacher = $('#form_teacher select[name = "id_teacher"]').val();
		if (validation(id_teacher)) {
		    let teacher = $('#teacher');
		    $(teacher).children().each(function (index, value){
		        if ($(value).val() === id_teacher) {
		            $(teacher).prop('selectedIndex', index);
                }
            });
		    $('#schedule_of_teacher').css('display', 'block');
		    $('#select_teacher').css('display', 'block');
		    $('#form_teacher').css('display', 'none');
            teacherUpdate(id_teacher);
		}
	});

	$(document).on('change', '#teacher', function (e) {
	    let id_teacher = $(this).val();
        e.preventDefault();
        teacherUpdate(id_teacher);
    });

	function teacherUpdate(id_teacher) {
	    let token = $('#form_teacher input[name = "_token"]').val();
		$.ajax({
			url: '{{ route('admin.schedule.requestAjax') }}',
			type: 'POST',
			dataType: 'JSON',
			data: {
			    _token: token,
                id_teacher: id_teacher,
                action: 'updateScheduleTeacher'
            },
            statusCode: {
			    422: function (xhr) {
                    $('#schedule_of_teacher').html('<h2>Schedule Of This Teacher Not Found</h2>');
                }
            },
			success: (res) => {
				$('#schedule_of_teacher').html(res['html']);
			}
		});
	}

	function validation(id_teacher) {
	    let error_teacher = $('.error-teacher');
	    if (id_teacher === null) {
	        $(error_teacher[0]).html('required').addClass('error');
	        return false;
        }

	    return true;
    }
</script>
@endpush
