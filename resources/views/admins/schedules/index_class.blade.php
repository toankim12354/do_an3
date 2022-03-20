@extends('admins.schedules.layout.index')

@section('title', __('schedules'))

@section('name_page', 'Schedule For Class')

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app/assigns/create.css') }}">
@endpush

@section('content.schedules')
<div class="">
    {{ Breadcrumbs::render() }}
</div>
    <form class="form-horizontal" id="form_grade" style="margin-top: 60px;">
        @csrf
        {{-- title --}}
        <div class="card-header" data-background-color="blue">
            <h4 class="card-title">CHOOSE GRADE</h4>
        </div>
        <div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
            <div class="col-md-4 form-group">
                <label>Grade</label>
                <select name="id_grade" title="Grade" class="select-grade select">
                    <option disabled selected>
                        Choose Grade
                    </option>
                    @foreach ($grades as $valueGrade)
                        <option value="{{ $valueGrade->id }}">
                            {{ $valueGrade->name }}
                        </option>
                    @endforeach
                </select>
                <small class="show-error error-grade"></small>
            </div>
        </div>
        <div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
            <div class="col-md-12 form-group">
                <button id="btnGrade" class="btn btn-success btn-round">Save</button>
            </div>
        </div>
    </form>
    <div id="select_grade" style="display: none;">
        <div style="margin-bottom: 100px;">
            <div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
                <div class="col-md-4 form-group">
                    <select class="input form-control" id="grade">
                        @foreach ($grades as $valueGrade)
                            <option value="{{ $valueGrade->id }}">
                                {{ $valueGrade->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div id="schedule_of_grade" style="display: none;">
{{--             @include('admins.schedules.schedules_grade')--}}
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
        $(document).on('click','#btnGrade', function (e) {
            e.preventDefault();
            let id_grade = $('#form_grade select[name = "id_grade"]').val();
            if (validation(id_grade)) {
                let grade = $('#grade');
                $(grade).children().each(function (index, value){
                    if ($(value).val() === id_grade) {
                        $(grade).prop('selectedIndex', index);
                    }
                });
                $('#schedule_of_grade').css('display', 'block');
                $('#select_grade').css('display', 'block');
                $('#form_grade').css('display', 'none');
                teacherUpdate(id_grade);
            }
        });

        $(document).on('change', '#grade', function (e) {
            let id_grade = $(this).val();
            e.preventDefault();
            teacherUpdate(id_grade);
        });

        function teacherUpdate(id_grade) {
            let token = $('#form_grade input[name = "_token"]').val();
            $.ajax({
                url: '{{ route('admin.schedule.requestAjax') }}',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    _token: token,
                    id_grade: id_grade,
                    action: 'updateScheduleGrade'
                },
                statusCode: {
                    422: function (xhr) {
                        $('#schedule_of_grade').html('<h2>Schedule Of This Grade Not Found</h2>');
                    }
                },
                success: (res) => {
                    $('#schedule_of_grade').html(res['html']);
                }
            });
        }

        function validation(id_grade) {
            let error_grade = $('.error-grade');
            if (id_grade === null) {
                $(error_grade[0]).html('required').addClass('error');
                return false;
            }

            return true;
        }
    </script>
@endpush
