<table>
	<thead>
		<tr>
			<th>Họ tên</th>
			<th>Ngày sinh</th>
			<th>Giới tính</th>
			<th>Điện thoại</th>
			<th>Địa chỉ</th>
			<th>Email</th>
			<th>Quyền</th>
		</tr>
	</thead>
	<tbody>
		@foreach($admins as $admin)
		<tr>
			<td>{{ $admin->name }}</td>
			<td>{{ $admin->dob }}</td>
			<td>{{ $admin->gender }}</td>
			<td>{{ $admin->phone }}</td>
			<td>{{ $admin->address }}</td>
			<td>{{ $admin->email }}</td>
			<td>{{ $admin->is_super ? "Super Admin" : "Admin" }}</td>
		</tr>
		@endforeach
	</tbody>
</table>