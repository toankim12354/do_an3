/**
 * [get_inputs description]
 * @param  {...[type]} selector [description]
 * @return {[type]}             [description]
 */
function get_inputs(...selector) {
	let data = {};
	for (let i in selector ) {
		console.log(i);
		let tmp = [];
		let key = selector[i].replace(/[\#\_\.\[\]]/g, '');
		key = key.replace(/[\-]/g, '_');

		$(selector[i]).each(function(k, v) {
			tmp.push($(v).val());
		});

		data[key] = tmp;
	}

	return data;
}

/**
 * [indexOfClass description]
 * @param  {[type]} collection [description]
 * @param  {[type]} element    [description]
 * @return {[type]}            [description]
 */
function indexOfClass(collection, element) {
	for (let i in collection) {
		if (collection[i] === element) { 
			return i; 
		}
	}

	return -1;
}