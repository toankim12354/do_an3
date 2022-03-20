@php
$attendances = $data->attendances;
$students = $data->students;
$statuses = $data->statuses;
@endphp

<table class="table table-bordered">
	<thead>
		<tr>
			<th rowspan="3">TÊN SINH VIÊN </th>
			<th colspan="{{ count($attendances) }}" class="text-center">NGÀY ĐIỂM DANH</th>
		</tr>
		<tr>
			@for ($i = 0; $i < count($attendances); $i++)
			<td class="text-center text-danger">{{ $i + 1 }}</td>
			@endfor
		</tr>
		<tr>
			@foreach ($attendances as $attendance)
			<td class="text-center">
				<span class="badge" style="background: blue;">
					<strong>{{ date('d-m', strtotime($attendance->created_at)) }}</strong>
				</span>
			</td>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($students as $student)
		<tr>
			<td style="min-width: 200px !important;">
				<span class="
				@if ($student->infoAttendance->absentPercent < 20)
				text-success
				@elseif ($student->infoAttendance->absentPercent == 20)
				text-warning
				@else
				text-danger
				@endif">
				{{ 	
					$student->name 
					. "(" 
					. $student->infoAttendance->timeAsAbsents
					. "/"
					. $student->infoAttendance->totalTimes
					. ")"
				}}
			</span>
		</td>

		@foreach ($attendances as $attendance)
		<td class="text-center">
			@php 
			$status = $statuses[$attendance->id][$student->id] 
			@endphp

			<select class="data" data-id-student="{{ $student->id }}" data-id-attendance="{{ $attendance->id }}">
				<option value="1"
				{{ $status == '1' ? 'selected' : '' }}>
				<span style="color: green">ĐH</span>
			</option>

			<option value="0"
			{{ $status == '0' ? 'selected' : '' }}>
			<span class="text-danger">N</span>
		</option>

		<option value="2"
		{{ $status == '2' ? 'selected' : '' }}>
		<span class="text-warning">M</span>
	</option>

	<option value="3"
	{{ $status == '3' ? 'selected' : '' }}>
	<span class="text-info">CP</span>
</option>

<option value=""
{{ $status === null ? 'selected' : 'disabled' }}>CN</option>
</select>
</td>
@endforeach
</tr>
@endforeach
</tbody>
</table>
{{-- <h1>{{ dd($data) }}</h1> --}}