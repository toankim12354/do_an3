<a data-toggle="collapse" href="#statistic{{ $subject->id }}" class="collapsed">
	<h2 class="alert">
		{{ $subject->name . "(" . count($assign->attendances) . " Tiet)" }}
		@if (count($students) > 0)
		<b class="caret"></b>
		@endif
	</h2>
</a>

<div class="collapse" id="statistic{{ $subject->id }}">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th class="text-left">MSV</th>
				<th class="text-left">Tên</th>
				<th class="text-left">Có mặt(buổi)</th>
				<th class="text-left">Vắng mặt(buổi)</th>
				<th class="text-left">Đi muộn(buổi)</th>
				<th class="text-left">Có phép(buổi)</th>
				<th class="text-left">Tổng thời gian nghỉ(buổi)</th>
				<th class="text-left">Đã nghỉ(%)</th>
				<th class="text-center">Ghi chú</th>
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
				<td class="text-success">
					@if ($hasAttendance)
						{{ $student->infoAttendance->presents ?? '-' }}
					@else
						{{ '-' }}
					@endif
				</td>
				<td class="text-danger">
					@if ($hasAttendance)
						{{ $student->infoAttendance->absents ?? '-' }}
					@else
						{{ '-' }}
					@endif
				</td>
				<td class="text-warning">
					@if ($hasAttendance)
						{{ $student->infoAttendance->lates ?? '-' }}
					@else
						{{ '-' }}
					@endif
				</td>
				<td class="text-info">
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
						{{ $student->infoAttendance->absentPercent ?? '-' }}
					@else
						{{ '-' }}
					@endif
				</td>
				<td class="text-center">
					@if ($hasAttendance)
						@php
							$absentPercent = $student->infoAttendance
													 ->absentPercent ?? 0
						@endphp
						@if ($absentPercent > '50')
							<span class="badge" style="background: red;">HỌC LẠI</span>
						@elseif ($absentPercent > '30')
							<span class="badge" style="background: orange;">THI LẠI</span>
						@else
							<span class="badge" style="background: green;">ĐƯỢC THI</span>
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
</div>


