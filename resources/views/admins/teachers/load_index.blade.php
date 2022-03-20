@foreach ($teachers as $teacher)
<tr>
	<td>{{ $teacher->id }}</td>
	<td>{{ $teacher->name }}</td>
	<td>{{ $teacher->email }}</td>
	<td>{{ $teacher->phone }}</td>
	<td>{{ $teacher->dob }}</td>
	<td>{{ $teacher->address }}</td>
	<td>{{ $teacher->gender }}</td>
	<td class="text-center">
		@if ($teacher->status)
			<span class="badge" style="background: green;">Active</span>
		@else
			<span class="badge" style="background: red;">Inactive</span>
		@endif
	</td>
	<td class="td-actions text-right">
		<a href="{{ route('admin.teacher-manager.show', $teacher->id) }}">
			<button type="button" rel="tooltip" class="btn btn-info btn-info btn-round" data-toggle="tooltip" title="xem và sửa" data-placement="left">
				<i class="material-icons">edit</i>
			</button>
		</a>
		@if (! $teacher->hasAssign())
			<a>
				<form action="{{ route('admin.teacher-manager.destroy', $teacher->id) }}" method="POST" style="display: inline-block;">
					@csrf
					@method('delete')
					<button type="submit" rel="tooltip" class="btn btn-danger btn-round" data-toggle="tooltip" title="xóa" data-placement="left" onclick="return confirm('Bạn có chắc muốn xóa giảng viên này?')">
						<i class="material-icons">close</i>
					</button>
		    	</form>
			</a>
		@endif
	</td>
</tr>
@endforeach
<tr>
	<td colspan="9">
		{{ $teachers->links() }}
	</td>
</tr>