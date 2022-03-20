@extends('admins.schedules.layout.index')

@section('title', __('Schedules'))

@section('name_page', 'Edit Schedules')

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
                <form class="form-horizontal" id="form" disabled="true">
                    @csrf
                    <input type="hidden" name="id_assign" value="{{ $assign->id }}" id="id_assign">
                    <div class="card-header" data-background-color="blue">
                        <h4 class="card-title">EDIT SCHEDULES</h4>
                    </div>
                    {{-- alert --}}
                    <div class="card-header">
                        <div id="message"></div>
                    </div>

                    <div class="card-content">
                        <div class="card-title">
                            <div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
                                <div class="col-md-12 form-group">
                                    <p><b>Grade</b>: <span id="grade">{{ $assign->grade->name }}</span> - <b>Subject</b>: <span id="subject">{{ $assign->subject->name }}</span> - <b>Teacher</b>: <span id="teacher">{{ $assign->teacher->name }}</span></p>
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
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($schedule as $value)
                                    <input type="hidden" name="id_schedule[]" value="{{ $value['id'] }}">
                                    <tr>
                                        <td class="col-input">
                                            <select name="id_class_room[]" title="Class Room" class="select-classroom select">
                                                <option disabled selected>
                                                    Choose Class Room
                                                </option>
                                                @foreach ($classrooms as $classroom)
                                                    <option value="{{ $classroom->id }}" <?php if ($value['id_class_room'] == $classroom->id){ echo "selected";} ?>>
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
                                                @for($i=1; $i<7; $i++)
                                                    <option value="{{ $i }}" <?php if($value['day'] == $i){echo "selected";} ?>>{{ "Thứ ".($i+1) }}</option>
                                                @endfor
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
                                                    <option value="{{ $lesson->id }}" <?php if ($value['id_lesson'] == $lesson->id){ echo "selected";} ?>>
                                                        {{ $lesson->start . " - " . $lesson->end}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="show-error error-lesson">

                                            </small>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row text-center">
                            <button class="btn btn-success btn-round" id="btnSubmit">SAVE</button>
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

            // clear error when select
            $(document).on('change', '.select', function() {
                $('.show-error').html('').removeClass('error');
            });
        });

        // submit function
        function submit() {
            let id_schedules = $('#form input[name = "id_schedule"]').val();
            $.ajax({
                url: '{{ route('admin.schedule.updateMultiSchedule') }}',
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

        // update days
        $(document).on('change', '.select-classroom', function() {
            let token = $('#form input[name = "_token"]').val();
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
            let token = $('#form input[name = "_token"]').val();
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

    </script>
@endpush
