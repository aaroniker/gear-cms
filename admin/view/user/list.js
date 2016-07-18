new Vue({
	el: '#user',
	data: {
		tableData: [],
		tableColumns: ['email'],
		searchString: ''
	},
	ready: function() {
		this.fetch();
	},
	methods: {
		fetch: function() {

			var vue = this;
			
			$.ajax({
				method: "POST",
				url: "/admin/user",
				dataType: "json",
				data: {}
			}).done(function(data) {
				vue.$set('tableData', data);
			});

		}
	}
});
