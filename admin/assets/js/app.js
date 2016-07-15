Vue.filter('lang', function (value) {
	if (value in lang)
		return lang[value];
	else
		return value;
});