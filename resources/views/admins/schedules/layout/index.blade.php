@extends('layouts.app')

@section('content')
<div class="card">

	<div class="card-content">
		@yield('content.schedules')
	</div>
</div>
@stop

{{-- @push('script')
<script type="text/javascript">
	$(function() {

		setTimeout(() => {
			$('.alert').remove();
		}, 5000);

		$('[data-toggle="tooltip"]').tooltip();
		
		// alert success when add new assign success
		// if (localStorage.getItem('assign_success') !== null) {
		// 	$('#message').html('').removeClass('alert alert-success');
		// 	$('#message').html(localStorage.getItem('assign_success'))
		// 	.addClass('alert alert-success');
		// 	localStorage.clear();
		// }

		// fetch data when choose row
		$(document).on('change', '#row', function() {
			fetch_page(...get_search());
		});

		// fetch data when choose grade
		$(document).on('change', '#filterGrade', function() {
			fetch_page(...get_search());
		});

		// fetch data when choose subject
		$(document).on('change', '#filterSubject', function() {
			fetch_page(...get_search());
		});

		// fetch data when choose teacher
		$(document).on('change', '#filterTeacher', function() {
			fetch_page(...get_search());
		});

		// fetch data when click search button
		$(document).on('click', '#btnSearch', function(e) {
			e.preventDefault();
		});

		// fetch data when switch page
		$(document).on('click', '.pagination a', function(e) {
			e.preventDefault();

			let page = $(this).attr('href').split('page=')[1];

			fetch_page(...get_search(), page);
		});
	});

	function fetch_page(row = 10, grade, subject, teacher, page = 1) {
		let url =  `{{ route('admin.assign.index') }}?row=${row}&grade=${grade}&subject=${subject}&teacher=${teacher}&page=${page}`;

		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'json',
			success: function(res) {
				$('tbody').html(res.html);
				$('[data-toggle="tooltip"]').tooltip();
			},
			error: function(res) {
				let error = res.responseJSON;

				// redirect if unauthenticate
				if (error.hasOwnProperty('url')) {
					window.location.replace(error.url);
				}
			}
		});
	}

	function get_search() {
		return [
		$('#row').val(), 
		$('#filterGrade').val(),
		$('#filterSubject').val(),
		$('#filterTeacher').val()
		];
	}
</script>
@endpush --}}