@extends('layouts.app')

@section('title', __('dashboard'))

@section('name_page', 'Dashboard')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card-header">
			{{ Breadcrumbs::render() }}
		</div>
	</div>
</div>

<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12 bg-info">
		<h3 class="text-center">XIN CHÀO GIÁO VỤ 
			<span class="text-warning">{{ strtoupper(Auth::user()->name) }}</span>
		</h3>
	</div>
</div>

<div class="row">
	<div class="row">
		<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats">
				<div class="card-header" data-background-color="rose">
					<i class="fas fa-chalkboard-teacher"></i>
				</div>
				<div class="card-content">
					<p class="category">GIẢNG VIÊN</p>
					<h3 class="card-title">{{ $counts['teachers'] ?? 0 }}</h3>
				</div>
				<div class="card-footer">
					<div class="stats">
						<i class="material-icons">local_offer</i>
						<a href="{{ route("admin.teacher-manager.index") }}">Quản lý</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats">
				<div class="card-header" data-background-color="orange">
					<i class="fas fa-user-graduate"></i>
				</div>
				<div class="card-content">
					<p class="category">SINH VIÊN</p>
					<h3 class="card-title">{{ $counts['students'] ?? 0 }}</h3>
				</div>
				<div class="card-footer">
					<div class="stats">
						<i class="material-icons">local_offer</i>
						<a href="{{ route("admin.student-manager.index") }}">Quản lý</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats">
				<div class="card-header" data-background-color="green">
					<i class="material-icons">store</i>
				</div>
				<div class="card-content">
					<p class="category">PHÒNG HỌC</p>
					<h3 class="card-title">{{ $counts['classrooms'] ?? 0 }}</h3>
				</div>
				<div class="card-footer">
					<div class="stats">
						<i class="material-icons">local_offer</i>
						<a href="{{ route("admin.classroom.index") }}">Quản lý</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-sm-6">
			<div class="card card-stats">
				<div class="card-header" data-background-color="blue">
					<i class="fas fa-book-reader"></i>
				</div>
				<div class="card-content">
					<p class="category">LỚP HỌC</p>
					<h3 class="card-title">{{ $counts['grades'] ?? 0 }}</h3>
				</div>
				<div class="card-footer">
					<div class="stats">
						<i class="material-icons">local_offer</i>
						<a href="{{ route("admin.grade.index") }}">Quản lý</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@push('script')
	<script type="text/javascript">
    $(document).ready(function() {

        // Javascript method's body can be found in assets/js/demos.js
        demo.initDashboardPageCharts();

        demo.initVectorMap();
    });
	</script>
@endpush