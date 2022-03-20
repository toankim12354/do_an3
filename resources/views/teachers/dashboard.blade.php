@extends('layouts.app')

@section('title', __('dashboard'))

@section('name_page', 'Dashboard')

@section('content')
<div>
	{{ Breadcrumbs::render() }}
</div>
<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12 bg-info">
		<h3 class="text-center">XIN CHÀO GIẢNG VIÊN  
			<span class="text-warning">{{ strtoupper(Auth::user()->name) }}</span>
		</h3>
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
