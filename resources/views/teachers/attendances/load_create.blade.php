@foreach ($listData->record as $data)
	{{-- {{ dd($data->a) }} --}}
	<tr>
		<td>
			{{ $data->code }}
			<input type="hidden" value="{{ $data->id }}" class="id" 
			id="id_{{ $data->id }}">
		</td>
		<td>
			<span class="
				@if ($data->infoAttendance->absentPercent < 20)
					text-success
				@elseif ($data->infoAttendance->absentPercent == 20)
					text-warning
				@else
					text-danger
				@endif">
			{{ 	
				$data->name 
				. "(" 
				. $data->infoAttendance->timeAsAbsents
				. "/"
				. $data->infoAttendance->totalTimes
				. ")"
			}}
			</span>
		</td>
		<td width="" style="">
			<div style="display: flex;">
				<div class="radio" style="margin-right: 70px;">
					<label style="color: green;">
						<input class="status_{{ $data->id }}" type="radio" 
						name="status_{{ $data->id }}"  value="1" {{ $data->status == '1' ? 'checked' : '' }}>Đi học 
					</label>
				</div>

				<div class="radio" style="margin-right: 70px;">
					<label style="color: red;">
						<input class="status_{{ $data->id }}" type="radio" 
						name="status_{{ $data->id }}" value="0" 
						{{ $data->status == '0' || is_null($data->status) ? 'checked' : '' }}>Nghỉ
					</label>
				</div>

				<div class="radio" style="margin-right: 70px;">
					<label style="color: orange;">
						<input class="status_{{ $data->id }}" type="radio"
						name="status_{{ $data->id }}" value="2" {{ $data->status == '2' ? 'checked' : '' }}>Muộn
					</label>
				</div>

				<div class="radio">
					<label style="color: blue;">
						<input class="status_{{ $data->id }}" type="radio"  
						name="status_{{ $data->id }}" value="3" {{ $data->status == '3' ? 'checked' : '' }}>Có phép
					</label>
				</div>	
			</div>
		</td>
		<td>
			<div class="form-group">
				<input class="note" style="width: 100%; padding: 5px;" type="text" value="{{ $data->note }}" 
				id="subNote_{{ $data->id }}">
			</div>
		</td>
	</tr>
@endforeach
<tr>
	<td colspan="4">
		<textarea name="mainNote" id="mainNote" rows="6" 
		style="width: 100%; border-radius: 10px; border-color: blue; padding: 5px;" data-value="{{ $listData->mainNote ?? '' }}"></textarea>
	</td>
</tr>
<tr>
	<td colspan="4">
		<div class="text-center">
			<button class="btn btn-success" type="button" id="saveBtn">LƯU </button>
		</div>
	</td>
</tr>