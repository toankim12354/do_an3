// render option for select by json
function render_option(setting = {}) {
	if (count_key(setting)) {
		let select = setting.select ? setting.select : '';
		let field = setting.field;
		let valueField = (field.valueField) ? field.valueField : '';
		let textField = (field.textField) ? field.textField : '';
		let attr = (setting.attr) ? setting.attr : '';
		let data = setting.data ? setting.data : null;
		let option = '';


		// build option
		if (data !== null && typeof data == 'object' 
			&& count_key(data) > 0) {

			$.each(setting.data, function(k, v) {
				let text = textField !== '' ? v[textField] : '';
				let value = valueField !== '' ? v[valueField] : '';

				option += `<option ${attr} value="${value}">
				${text}
				</option>`
			});
	}

	switch (setting.type) {
		case 'fresh':
				// build default option
				if (setting.default !== undefined) {
					let attrOptionDefault = "";
					let textOptionDefault = ""; 

					if (setting.default.attr) {
						attrOptionDefault = setting.default.attr;
					}

					if (setting.default.text) {
						textOptionDefault = setting.default.text;
					}

					option = `<option ${attrOptionDefault}>
					${textOptionDefault}
					</option>${option}`;
					console.log(option);
				}

				// // build option
				// $.each(setting.data, function(k, v) {
				// 	let text = textField !== '' ? v[textField] : '';
				// 	let value = valueField !== '' ? v[valueField] : '';

				// 	option += `<option ${attr} value="${value}">
				// 				${text}
				// 			   </option>`
				// })

				// fresh option of select
				$(select).html(option);

				// if select has class 'selecpicker' -> refresh
				if ($(select).hasClass('selectpicker')) {
					console.log('ok');
					$(select).selectpicker('refresh');
				}

				break;

				case 'append':
				// append option to select
				$(select).append(option);

				// if select has class 'selecpicker' -> refresh
				if ($(select).hasClass('selectpicker')) {
					console.log('ok');
					$(select).selectpicker('refresh');
				}
				break;

				default:
				break;
			}
		}
	}

// count attribute of object
function count_key(object) {
	key = [];

	for (let i in object) {
		key.push(i);
	}

	return key.length;
}

// render alert
function render_alert(type = '', message = '', selector = '', hideAfter = null) {
	let alertClass = '';
	let alertTitle = '';
	let alertMessage = message;

	switch (type) {
		case 'error':
		alertClass = 'alert-danger';
		alertTitle = 'Error!';
		break;

		case 'warning':
		alertClass = 'alert-warning';
		alertTitle = 'Warning!';
		break;

		case 'success':
		alertClass = 'alert-success';
		alertTitle = 'Success!';
		break;

		default:
		break;
	}

	let alert = `
	<div class="alert alert-dismissable ${alertClass}">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="margin-right: 20px;">
	<span aria-hidden="true">&times;</span>
	</button>
	<strong>${alertTitle}</strong> ${alertMessage}
	</div>`;

	// display
	$(selector).html(alert).show();

	// hide after time
	if (hideAfter !== null && !isNaN(hideAfter)) {
		hide_after(selector, hideAfter);
	}
}

// hide element after time
function hide_after(selector, time) {
	setTimeout(() => {
		$(selector).hide();
	}, time);
}

// render error by selection
function render_error(options) {
	for (option of options) {
		if ('element' in option) {
			let element, index, message, klass;

			if (Array.isArray(option.element)) {
				element = option.element;

				index   = ('index' in option) 
				? option.index : null;

				message = ('message' in option) 
				? option.message : '';

				klass   = ('class' in option) 
				? option.class : '';

				if (index !== null) {
					for (let i of index) {
						for (let e of element) {
							$($(e)[i]).html(message)
							.addClass(klass);
						}
					}
				} else {
					for (let e of element) {
						$(e).html(message)
						.addClass(klass);
					}
				}
			} else {
				element = $(option.element);

				index   = ('index' in option) 
				? option.index : null;

				message = ('message' in option) 
				? option.message : '';

				klass   = ('class' in option) 
				? option.class : '';

				if (index !== null) {
					for (i of index) {
						$(element[i]).html(message).addClass(klass);
					}
				} else {
					$(element).html(message).addClass(klass);
				}
			}
		}
	}
}

// remove class
function remove_class(selector, klass) {
	$(selector).removeClass(klass);
}

// clear error
function clear_error(selector, classError = '') {
	if (Array.isArray(selector)) {
		for (let e of selector) {
			if (classError !== '') {
				$(e).html('').removeClass(classError);
			}
		}
	} else {
		$(selector).html('').removeClass(classError);
	}
}

