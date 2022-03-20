@extends('layouts.app')

@section('title', __('teacher'))

@section('name_page', 'Lịch dạy')

@section('content')
<div>
	{{ Breadcrumbs::render() }}
</div>
<div class="container-fluid">
	<div id="calendar"></div>
</div>
@stop

@push('script')
<script type="text/javascript">
	$(function() {
		let calendar = $('#calendar').fullCalendar({
			header: {
                left: 'title',
                center: 'month,agendaWeek,agendaDay',
                right: 'prev,next,today'
            },
            
            events: '{{ route('teacher.work.schedule') }}'
		});
	});
</script>
@endpush
