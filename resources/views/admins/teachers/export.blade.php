<table>
	<thead>
		<tr>
			<th>Họ tên</th>
			<th>Ngày sinh</th>
			<th>Giới tính</th>
			<th>Điện thoại</th>
			<th>Địa chỉ</th>
			<th>Email</th>
		</tr>
	</thead>
	<tbody>
		@foreach($teachers as $teacher)
		<tr>
			<td>{{ $teacher->name }}</td>
			<td>{{ $teacher->dob }}</td>
			<td>{{ $teacher->gender }}</td>
			<td>{{ $teacher->phone }}</td>
			<td>{{ $teacher->address }}</td>
			<td>{{ $teacher->email }}</td>
		</tr>
		@endforeach
	</tbody>
</table>