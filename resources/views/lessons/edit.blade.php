@extends('layouts.app')

@section('title', 'Lesson Edit')
@section('name_page', 'Lesson Edit')

@section('content')
<div class="card">
	<div class="card-header card-header-icon" data-background-color="rose">
		<i class="material-icons">assignment</i>
	</div>
	<div class="card-content">
		<form action="{{ route('admin.lesson.update', $lesson->id) }}" method="POST">
		@csrf
		@method('PUT')
		<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
				<div class="col-md-2 form-group">
					<label>Time Start</label>
					<input type="time" name="start" class="input form-control" value="{{ $lesson->start }}">
					@error('start')
						<span class="invalid-feedback text-danger" role="alert">
					        {{ $message }}
					    </span>
					@enderror
				</div>
				<div class="col-md-2 form-group">
					<label>Time End</label>
					<input type="time" name="end" class="input form-control" value="{{ $lesson->end }}">
					@error('end')
						<span class="invalid-feedback text-danger" role="alert">
					        {{ $message }}
					    </span>
					@enderror
				</div>
				<div class="col-md-2 form-group" style="display:flex; align-items:center;">
					<input type="submit" value="Submit" class="btn btn-success btn-round">
				</div>
			</div>
            <div style="width: 90%; text-align: center;">
                <p style="color: red; display: none" id="error_less_time">Time start must be less than time end</p>
            </div>
	</form>
	</div>
</div>
<table width="410px">
	<tr align="center">
		<td width="210px">
			@error('start')
				<span class="invalid-feedback" role="alert">
			        {{ $message }}
			    </span>
			@enderror
		</td>
		<td width="200px">
			@error('end')
				<span class="invalid-feedback" role="alert">
			        {{ $message }}
			    </span>
			@enderror
		</td>
	</tr>
</table>
@stop
@push('script')
    <script type="text/javascript">

        $('input[type="submit"]').on('click', function (e) {
            var start = $('input[name="start"]').val();
            var end = $('input[name="end"]').val();
            var error = $('#error_less_time');
            var feedback = $('.invalid-feedback');

            if (end <= start) {
                e.preventDefault();
                error.css('display', 'block');
                feedback.css('display', 'none');
                return false;
            } else {
                error.css('display', 'none');
                feedback.css('display', 'block');
            }
        });
    </script>
@endpush
