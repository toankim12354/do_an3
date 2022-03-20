@extends('layouts.app')

@section('title', __('assign'))

@section('name_page', 'List assign')

@section('content')
<div class="row" style="display: flex; justify-content: center;">
	<div class="col-md-8">
		<div class="card">
			<form method="post" action="
			{{ route('admin.assign.update', $assign->id) }}" class="form-horizontal">
				@csrf
				@method('put')
				{{-- <input type="hidden" name="id" value="{{ $assign->id }}"> --}}
				<div class="card-header card-header-text" data-background-color="rose">
					<h4 class="card-title">Assign Detail</h4>
				</div>

				<div class="card-content">
					<div class="row">
						<label class="col-sm-2 label-on-right">Grade</label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<select name="id_grade" id="" class="selectpicker" data-style="select-with-transition" title="Choose Grade">
									<option value="" disabled>Choose Grade</option>
									@foreach ($grades as $grade)
										<option value="{{ $grade->id }}" 
											@if (old('id_grade') !== null)
												{{ $grade->id == old('id_grade')
													? 'selected' : ''}}
											@else
												{{ $grade->id 
													== $assign->grade->id 
													? 'selected' : '' }}
											@endif
										>
											{{ $grade->name . $grade->yearSchool->name }}
										</option>
									@endforeach
								</select>
								@error('id_grade')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Subject</label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<select name="id_subject" id="" class="selectpicker" data-style="select-with-transition" title="Choose Subject">
									<option value="" disabled>Choose Subject</option>
									@foreach ($subjects as $subject)
										<option value="{{ $subject->id }}" 
											@if (old('id_subject') !== null)
												{{ $subject->id 
													== old('id_subject')
													? 'selected' : ''}}
											@else
												{{ $subject->id 
													== $assign->subject->id 
													? 'selected' : '' }}
											@endif
										>
											{{ $subject->name }}
										</option>
									@endforeach
								</select>
								@error('id_subject')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Teacher</label>
						<div class="col-sm-10">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<select name="id_teacher" id="" class="selectpicker" data-style="select-with-transition" title="Choose Teacher">
									<option value="" disabled>Choose Teacher</option>
									@foreach ($teachers as $teacher)
										<option value="{{ $teacher->id }}" 
											@if (old('id_teacher') !== null)
												{{ $teacher->id 
													== old('id_teacher')
													? 'selected' : ''}}
											@else
												{{ $teacher->id 
													== $assign->teacher->id 
													? 'selected' : '' }}
											@endif
										>
											{{ $teacher->name }}
										</option>
									@endforeach
								</select>
								@error('id_teacher')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<label class="col-sm-2 label-on-right">Status</label>
						<div class="col-sm-3">
							<div class="form-group label-floating is-empty">
								<label class="control-label"></label>
								<select name="status" id="" class="selectpicker" data-style="select-with-transition" title="Choose Status">
									<option value="" disabled>Choose Status</option>
									<option value="1"
										@if (old('status') !== null))
											{{ old('status') == '1' 
												? 'selected' : '' }}
										@else
											{{ $assign->status == '1' 
												? 'selected' : '' }}
										@endif
									>
										Active
									</option>
									<option value="0"
										@if (old('status') !== null))
											{{ old('status') == '0' 
												? 'selected' : '' }}
										@else
											{{ $assign->status == '0' 
												? 'selected' : '' }}
										@endif
									>
										Inactive
									</option>
									<option value="2"
										@if (old('status') !== null))
											{{ old('status') == '2' 
												? 'selected' : '' }}
										@else
											{{ $assign->status == '2' 
												? 'selected' : '' }}
										@endif
										disabled 
									>
										Moved
									</option>
									<option value="3"
										@if (old('status') !== null))
											{{ old('status') == '3' 
												? 'selected' : '' }}
										@else
											{{ $assign->status == '3' 
												? 'selected' : '' }}
										@endif
										disabled 
									>
										Done
									</option>
								</select>
								@error('status')
									<div class="alert alert-danger">
										{{ $message }}
									</div>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-center">
							<button class="btn btn-success btn-round">save</button>
							<button type="reset" class="btn btn-warning btn-round" onclick="location.reload()">reset</button>
							<button type="button" class="btn btn-danger btn-round" onclick="window.location.replace('{{ route('admin.assign.index') }}')">back</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@stop
@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        demo.initFormExtendedDatetimepickers();
    });
</script>
@endpush