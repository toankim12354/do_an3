function validation() {
	let data = get_inputs(
			'.select-grade', 
			'.select-subject', 
			'.select-teacher', 
			'.select-start'
		);

	let grades   = data.select_grade;
	let subjects = data.select_subject;
	let teachers = data.select_teacher;
	let starts   = data.select_start;

	return handle_error(grades, subjects, teachers, starts);

}

function handle_error(grades, subjects, teachers, starts) {
	let validated = true;

	let allErrors     = $('.show-error');
	let errorGrades   = $('.error-grade');
	let errorSubjects = $('.error-subject');
	let errorTeachers = $('.error-teacher');
	let errorStarts   = $('.error-start');

	// clear all error
	$(allErrors).html('').removeClass('error');

	// check null
	$(grades).each(function(k, v) {
		if (v === null) {
			validated = false;
			$(errorGrades[k]).html('required').addClass('error');
		}
	});

	$(subjects).each(function(k, v) {
		if (v === null) {
			validated = false;
			$(errorSubjects[k]).html('required').addClass('error');
		}
	});

	$(teachers).each(function(k, v) {
		if (v === null) {
			validated = false;
			$(errorTeachers[k]).html('required').addClass('error');
		}
	});

	$(starts).each(function(k, v) {
		if (v == '') {
			validated = false;
			$(errorStarts[k]).html('required').addClass('error');
		}
	});

	// check duplicate
	if (validated) {
		for (let i = 0; i < grades.length - 1; ++i) {
			let index = [grades[i], subjects[i], teachers[i]];

			for (let j = i + 1; j < grades.length; ++j) {
				let row = [grades[j], subjects[j], teachers[j]];

				if (compare_array(row, index)) {
					validated = false;

					$(errorGrades[i]).html('duplicate').addClass('error');
					$(errorSubjects[i]).html('duplicate').addClass('error');
					$(errorTeachers[i]).html('duplicate').addClass('error');

					$(errorGrades[j]).html('duplicate').addClass('error');
					$(errorSubjects[j]).html('duplicate').addClass('error');
					$(errorTeachers[j]).html('duplicate').addClass('error');
				}
			}
		}
	}

	return validated;
}
