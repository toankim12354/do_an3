@extends('layouts.app')

@section('title', 'Lessons')
@section('name_page', 'Lessons')

@section('content')
<div class="card">
	<div class="card-header card-header-icon" data-background-color="rose">
		<i class="material-icons">assignment</i>
	</div>
	<div class="card-content">
		<form action="{{ route('admin.lesson.store') }}" method="POST">
			@csrf
			<div class="row text-center" style="text-align: center; display:flex; justify-content:center;">
				<div class="col-md-2 form-group">
					<label>Time Start</label>
					<input type="time" name="start" class="input form-control" value="{{ old('start') }}">
					@error('start')
						<span class="invalid-feedback text-danger" role="alert">
					        {{ $message }}
					    </span>
					@enderror
				</div>
				<div class="col-md-2 form-group">
					<label>Time End</label>
					<input type="time" name="end" class="input form-control" value="{{ old('end') }}">
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
		@if(Session::has('message'))
			@if(Session::get('message')['status'] == true)
				<div class="alert alert-success">{{ Session::get('message')['content'] }}</div>
			@endif
			@if(Session::get('message') == false)
				<div class="alert alert-danger">{{ Session::get('message')['content'] }}</div>
			@endif
		@endif
		<div class="table-responsive" style="margin-top:25px;">
			<table class="table">
				<thead>
					<tr>
					<th>Id</th>
					<th>Time Start</th>
					<th>Time End</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
					@foreach($lessons as $lesson)
					<tr>
						<td>{{ $lesson->id }}</td>
						<td>{{ $lesson->start }}</td>
						<td>{{ $lesson->end }}</td>
						<td>
							<a data-toggle="tooltip" title="Edit" href="{{ route('admin.lesson.edit', $lesson->id) }}" class="btn btn-info btn-round" data-placement="left">
								<i class="material-icons">edit</i>
							</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div>{{ $lessons->links() }}</div>
	</div>
</div>
@push('script')
<script type="text/javascript">
	$('[data-toggle="tooltip"]').tooltip();

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
@stop
