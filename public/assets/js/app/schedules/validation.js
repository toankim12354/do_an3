function validationSchedule() {
	let data = get_inputs(
		'.select-classroom', '.select-day', '.select-lesson'
		);

	let classrooms = data.select_classroom;
	let days = data.select_day;
	let lessons = data.select_lesson;

	return handle_error_schedule(classrooms, days, lessons);

}

function handle_error_schedule(classrooms, days, lessons) {
	let validated = true;

	let allErrors = $('.show-error');
	let errorClassrooms = $('.error-classroom');
	let errorDays = $('.error-day');
	let errorLessons = $('.error-lesson');

	// clear all error
	$(allErrors).html('').removeClass('error');

	// check null
	$(classrooms).each(function(k, v) {
		if (v === null) {
			validated = false;
			$(errorClassrooms[k]).html('required').addClass('error');
		}
	});

	$(days).each(function(k, v) {
		if (v === null) {
			validated = false;
			$(errorDays[k]).html('required').addClass('error');
		}
	});

	$(lessons).each(function(k, v) {
		if (v === null) {
			validated = false;
			$(errorLessons[k]).html('required').addClass('error');
		}
	});

	// check duplicate
	if (validated) {
		for (let i = 0; i < classrooms.length - 1; ++i) {
			let index = [classrooms[i], days[i], lessons[i]];

			for (let j = i + 1; j < classrooms.length; ++j) {
				let row = [classrooms[j], days[j], lessons[j]];

				if (compare_array(row, index)) {
					validated = false;

					$(errorClassrooms[i]).html('duplicate').addClass('error');
					$(errorDays[i]).html('duplicate').addClass('error');
					$(errorLessons[i]).html('duplicate').addClass('error');

					$(errorClassrooms[j]).html('duplicate').addClass('error');
					$(errorDays[j]).html('duplicate').addClass('error');
					$(errorLessons[j]).html('duplicate').addClass('error');
				}
			}
		}
	}

	return validated;
}
