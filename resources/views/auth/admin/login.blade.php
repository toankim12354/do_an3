@extends('layouts.auth')

@section('content')

<div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
	<form method="post" action="{{ route('login.admin') }}">
		@csrf

		<div class="card card-login card-hidden">
			<div class="card-header text-center" data-background-color="green">
				<h4 class="card-title">Admin Login</h4>
			</div>
			<p class="category text-center">
				Welcome to Admin Page
			</p>
			<br>

			{{-- hien thi thong bao loi neu xac thuc khong thanh cong --}}
			@if (Session::has('msg'))
			<div class="card-header text-center">
				<h5 class="alert alert-danger">{{ Session::get('msg') }}</h5>
			</div>
			@endif

			<div class="card-content">
				<div class="input-group">
					<span class="input-group-addon">
						<i class="material-icons">email</i>
					</span>
					<div class="form-group label-floating">
						<label class="control-label">Email address</label>
						<input type="email" class="form-control" name="email"
						value="{{ old('email') }}">
						@error('email')
						<div class="alert alert-danger">{{ $message }}</div>
						@enderror
					</div>

				</div>
				<div class="input-group">
					<span class="input-group-addon">
						<i class="material-icons">lock_outline</i>
					</span>
					<div class="form-group label-floating">
						<label class="control-label">Password</label>
						<input type="password" class="form-control" name="password" value="{{ old('password') }}">
						@error('password')
						<div class="alert alert-danger">{{ $message }}</div>
						@enderror
					</div>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="remember"> Remember me
					</label>
				</div>
			</div>

			<div class="footer text-center">
				<button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg">Sign in</button>
			</div>
		</div>
	</form>
</div>
@stop
@push('script')
<script type="text/javascript">
    $().ready(function() {
        demo.checkFullPageBackgroundImage();

        setTimeout(function() {
            // after 1000 ms we add the class animated to the login/register card
            $('.card').removeClass('card-hidden');
        }, 700)
    });
</script>
@endpush