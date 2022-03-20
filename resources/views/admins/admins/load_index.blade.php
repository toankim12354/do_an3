@foreach ($admins as $admin)
<tr>
	<td>{{ $admin->id }}</td>
	<td>
		{{ $admin->name }}
		@if (Auth::id() == $admin->id)
			<strong class="badge" 
			style="background: lawngreen; display: inline-block; margin-left: 2px;">BẠN</strong>
		@endif
		@if ($admin->is_super)
			<span class="badge" style="background: red; display: inline-block; margin-left: 2px;"
			data-toggle="tooltip" title="Super Admin" data-placement="right">SA</span>
		@else
			<span class="badge" style="background: cornflowerblue; display: inline-block; margin-left: 2px;"
			data-toggle="tooltip" title="Admin" data-placement="right">A</span>
		@endif
	</td>
	<td>{{ $admin->email }}</td>
	<td>{{ $admin->phone }}</td>
	<td>{{ $admin->dob }}</td>
	<td>{{ $admin->address }}</td>
	<td>{{ $admin->gender }}</td>
	<td class="text-center">
		@if ($admin->status)
			<span class="badge" style="background: green;">Active</span>
		@else
			<span class="badge" style="background: red;">Inactive</span>
		@endif
	</td>
	<td class="td-actions text-right">
		@if ($admin->is_super && Auth::id() == $admin->id)
			<a href="{{ route('profile.show') }}">
				<button type="button" rel="tooltip" class="btn btn-round btn-success" data-toggle="tooltip" title="Tài khoản của tôi" data-placement="left">
					MP
				</button>
		    </a>
		@endif

		@if ($admin->is_super && Auth::id() != $admin->id)
			<a href="{{ route('admin.admin-manager.show', $admin->id) }}">
				<button type="button" rel="tooltip" class="btn btn-primary btn-round" data-toggle="tooltip" title="View" data-placement="left">
					<i class="material-icons">person</i>
				</button>
		    </a>
		@endif

		@if (! $admin->is_super)
			<a href="{{ route('admin.admin-manager.show', $admin->id) }}">
				<button type="button" rel="tooltip" class="btn btn-info btn-round" data-toggle="tooltip" title="xem và sửa" data-placement="left">
					<i class="material-icons">edit</i>
				</button>
		    </a>
			<a>
				<form action="{{ route('admin.admin-manager.destroy', $admin->id) }}" method="POST" style="display: inline-block;">
					@csrf
					@method('delete')
					<button type="submit" rel="tooltip" class="btn btn-danger btn-round" data-toggle="tooltip" title="xóa" data-placement="left" onclick="return confirm('Bạn có chắc muốn xóa giáo vụ này?')">
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
		{{ $admins->links() }}
	</td>
</tr>