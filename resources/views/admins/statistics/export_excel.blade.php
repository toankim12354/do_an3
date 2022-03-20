<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th class="text-left">ID</th>
			<th class="text-left">Tên</th>
			<th class="text-left">Có mặt(buổi)</th>
			<th class="text-left">Vắng mặt(buổi)</th>
			<th class="text-left">Đi muộn(buổi)</th>
			<th class="text-left">Có phép(buổi)</th>
			<th class="text-left">Tổng thời gian nghỉ(buổi)</th>
			<th class="text-left">Ghi chú</th>
		</tr>
	</thead>
	<tbody>
		@if (count($students) > 0)
		@php
		$hasAttendance = (count($assign->attendances) > 0) 
		? true : false
		@endphp
		@foreach ($students as $student)
		<tr>
			<td>
				{{ $student->code ?? ''  }}
			</td>
			<td>
				{{ $student->name ?? '' }}
			</td>
			<td>
				@if ($hasAttendance)
				{{ $student->infoAttendance->presents ?? '-' }}
				@else
				{{ '-' }}
				@endif
			</td>
			<td>
				@if ($hasAttendance)
				{{ $student->infoAttendance->absents ?? '-' }}
				@else
				{{ '-' }}
				@endif
			</td>
			<td>
				@if ($hasAttendance)
				{{ $student->infoAttendance->lates ?? '-' }}
				@else
				{{ '-' }}
				@endif
			</td>
			<td>
				@if ($hasAttendance)
				{{ $student->infoAttendance->hasReasons ?? '-' }}
				@else
				{{ '-' }}
				@endif
			</td>
			<td>
				@if ($hasAttendance)
				{{ $student->infoAttendance->timeAsAbsents ?? '-' }}
				@else
				{{ '-' }}
				@endif
			</td>
			<td>
				@if ($hasAttendance)
				@php
				$absentPercent = $student->infoAttendance
				->absentPercent ?? 0
				@endphp
				@if ($absentPercent > '50')
				<span class="text-danger">HỌC LẠI</span>
				@elseif ($absentPercent > '30')
				<span class="text-warning" >THI LẠI</span>
				@else
				<span class="text-success" >ĐƯỢC THI</span>
				@endif
				@else
				{{ '-' }}
				@endif
			</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="4">
				TỔNG SỐ BUỔI ĐÃ DẠY CỦA MÔN NÀY
			</td>
			<td colspan="4">{{ count($assign->attendances) }}</td>
		</tr>
		@else
		KHÔNG CÓ DỮ LIỆU
		@endif
	</tbody>
</table>