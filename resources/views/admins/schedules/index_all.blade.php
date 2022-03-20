@extends('admins.schedules.layout.index')

@section('title', __('schedules'))

@section('name_page', 'Schedule All')

@section('content.schedules')
<div class="">
	{{ Breadcrumbs::render() }}
</div>
<div class="card-title" style="display: flex; justify-content: space-between; align-items: center;">

{{-- number of row to show --}}
<div class="col-lg-1 col-md-1 col-sm-1">
	<select class="selectpicker" data-style="select-with-transition" title="Choose gender" id="row">
		<option value disabled>Choose row</option>
		<option value="10" selected>10</option>
		<option value="25">25</option>
		<option value="50">50</option>
		<option value="100">100</option>
	</select>
</div>

{{-- filter classroom --}}
<div class="col-lg-3 col-md-3 col-sm-3">
	<select class="selectpicker" data-style="select-with-transition" title="Choose Class Room" data-size="7" id="filterClassroom">
		<option value disabled> Choose Class Room</option>
		<option value="">All</option>
		@foreach ($classrooms as $classroom)
		<option value="{{ $classroom->id }}">
			{{ $classroom->name }}
		</option>
		@endforeach
	</select>
</div>

{{-- filter day --}}
<div class="col-lg-3 col-md-3 col-sm-3">
	<select class="selectpicker" data-style="select-with-transition" title="Choose Day" data-size="7" id="filterDay">
		<option value disabled> Choose Subject</option>
		<option value="">All</option>
        @for($i=1; $i<7; $i++)
            <option value="{{ $i }}">
                {{ "Thứ ". ($i+1) }}
            </option>
        @endfor
	</select>
</div>

{{-- filter lesson--}}
<div class="col-lg-3 col-md-3 col-sm-3">
	<select class="selectpicker" data-style="select-with-transition" title="Choose Lesson" data-size="7" id="filterLesson">
		<option value disabled> Choose Lesson</option>
		<option value="">All</option>
		@foreach ($lessons as $lesson)
		<option value="{{ $lesson->id }}">
			{{ $lesson->start." - ".$lesson->end }}
		</option>
		@endforeach
	</select>
</div>

{{-- add --}}
<a href="{{ route('admin.schedule.create') }}">
	<button class="btn btn-success btn-round"
	data-toggle="tooltip" title="Add New Schedule" data-placement="left" style="padding-left: 14px; padding-right: 14px;">
		<i class="fas fa-plus fa-lg"></i>
	</button>
</a>
</div>

<div class="card-header">
{{-- alert success --}}
@if (session('success'))
<div class="alert alert-dismissable alert-success">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 20px;">
		<span aria-hidden="true">&times;</span>
	</button>
	<strong>Success!</strong> {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-dismissable alert-danger">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 20px;">
		<span aria-hidden="true">&times;</span>
	</button>
	<strong>Failed!</strong> {{ session('error') }}
</div>
@endif
</div>

<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<th>Grade</th>
			<th>Subject</th>
			<th>Teacher</th>
			<th>Class Room</th>
			<th>Day</th>
			<th>Lesson</th>
			<th>Start At</th>
			<th>Status</th>
			<th class="text-right">Action</th>
		</tr>
	</thead>
	<tbody>
		@include('admins.schedules.load_index_all')
	</tbody>
</table>
</div>
@stop
@push('script')
    <script type="text/javascript">
        $(function() {

            setTimeout(() => {
                $('.alert').remove();
            }, 5000);

            $('[data-toggle="tooltip"]').tooltip();

            // fetch data when choose row
            $(document).on('change', '#row', function() {
                fetch_page(...get_search());
            });

            // fetch data when choose classroom
            $(document).on('change', '#filterClassroom', function() {
                fetch_page(...get_search());
            });

            // fetch data when choose day
            $(document).on('change', '#filterDay', function() {
                fetch_page(...get_search());
            });

            // fetch data when choose lesson
            $(document).on('change', '#filterLesson', function() {
                fetch_page(...get_search());
            });

            // fetch data when click search button
            $(document).on('click', '#btnSearch', function(e) {
                e.preventDefault();
            });

            // fetch data when switch page
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();

                let page = $(this).attr('href').split('page=')[1];

                fetch_page(...get_search(), page);
            });
        });

        function fetch_page(row = 10, classroom, day, lesson, page = 1) {
            let url =  `{{ route('admin.schedule.indexAll') }}?row=${row}&classroom=${classroom}&day=${day}&lesson=${lesson}&page=${page}`;

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    console.log('hihi');
                    $('tbody').html(res.html);
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }

        function get_search() {
            return [
                $('#row').val(),
                $('#filterClassroom').val(),
                $('#filterDay').val(),
                $('#filterLesson').val()
            ];
        }
    </script>
@endpush
