@foreach ($students as $student)
<tr>
	<td>{{ $student->id }}</td>
	<td>{{ $student->name }}</td>
	<td>{{ $student->code }}</td>
	<td>{{ $student->email }}</td>
	<td>{{ $student->phone }}</td>
	<td>{{ $student->dob }}</td>
	<td>{{ $student->address }}</td>
	<td>{{ $student->gender }}</td>
	<td>{{ $student->grade->name }}</td>
	<td class="text-center">
		@if ($student->status)
			<span class="badge" style="background: green;">Active</span>
		@else
			<span class="badge" style="background: red;">Inactive</span>
		@endif
	</td>
	<td class="td-actions text-center">
		<a href="{{ route('admin.student-manager.show', $student->id) }}">
			<button type="button" rel="tooltip" class="btn btn-info btn-info btn-round" data-toggle="tooltip" title="xem và sửa" data-placement="left">
				<i class="material-icons">edit</i>
			</button>
		</a>
		<form action="{{ route('admin.student-manager.destroy', $student) }}" method="POST" style="display: inline-block;">
				@csrf
				@method('delete')
				<button type="submit" rel="tooltip" class="btn btn-danger btn-round" data-toggle="tooltip" title="xóa" data-placement="left" onclick="return confirm('bạn có chắc muốn xóa sinh viên này?')">
					<i class="material-icons">close</i>
				</button>
	    </form>
	</td>
</tr>
@endforeach
<tr>
	<td colspan="11">
		{{ $students->links() }}
	</td>
</tr>