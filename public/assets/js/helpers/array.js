function compare_array(a, b) {
	if (a instanceof Array && b instanceof Array) {
		let i = a.length;
		if (i != b.length) return false;

		while (i--) {
			if (a[i] !== b[i]) return false;
		}
	} else {
		return false;
	}

	return true;
}