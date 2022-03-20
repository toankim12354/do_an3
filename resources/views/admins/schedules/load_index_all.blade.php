@foreach ($schedules as $schedule)
<tr>
	{{-- {{ $schedule }} --}}
	<td>{{ $schedule->assign->grade->name }}</td>
	<td>{{ $schedule->assign->subject->name }}</td>
	<td>{{ $schedule->assign->teacher->name }}</td>
	<td>{{ $schedule->classRoom->name }}</td>
	<td>{{ "Thứ " . ($schedule->day+1) }}</td>
	<td>{{ $schedule->lesson->start . __(' - ') . $schedule->lesson->end }}</td>
    <td>{{ $schedule->assign->start_at }}</td>

    <td class="text-center">
		@if(isset($schedule->day_finish))
			{{ __('done') }}
		@else
			{{ __('active') }}
		@endif
	</td>
	<td class="td-actions text-right">
		<a href="{{ route('admin.schedule.edit', $schedule->assign->id) }}">
			<button type="button" rel="tooltip" class="btn btn-info btn-info btn-round" data-toggle="tooltip" title="View and Edit" data-placement="left">
				<i class="material-icons">edit</i>
			</button>
		</a>
	</td>
</tr>
@endforeach
<tr>
	<td colspan="6">
		{{ $schedules->links() }}
	</td>
</tr>
