<table>
	<thead>
		<tr>
			<th>Mã sinh viên</th>
			<th>Họ tên</th>
			<th>Ngày sinh</th>
			<th>Giới tính</th>
			<th>Điện thoại</th>
			<th>Địa chỉ</th>
			<th>Email</th>
			<th>Lớp</th>
		</tr>
	</thead>
	<tbody>
		@foreach($students as $student)
		<tr>
			<td>{{ $student->code }}</td>
			<td>{{ $student->name }}</td>
			<td>{{ $student->dob }}</td>
			<td>{{ $student->gender }}</td>
			<td>{{ $student->phone }}</td>
			<td>{{ $student->address }}</td>
			<td>{{ $student->email }}</td>
			<td>{{ $grade->name }}</td>
		</tr>
		@endforeach
	</tbody>
</table>