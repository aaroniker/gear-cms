new Vue({
	el: '#user',
	data: {
		users: [],
        checked: []
	},
	ready: function() {
		this.fetch();	
	},
	computed: {
		checkAll: {
			get: function () {
				return this.users ? this.checked.length == this.users.length : false;
			},
			set: function (value) {
				var checked = [];
				
				if(value) {
					this.users.forEach(function(user) {
						checked.push(user.id);
					});
				}
		
				this.checked = checked;
			}
		}
	},
	methods: {
		
		fetch: function() {
			
			var vue = this;
			
			$.ajax({
				method: "POST",
				url: "/admin/user",
				dataType: "json",
				data: {}
			}).done(function(users) {
				vue.$set('users', users);
			});
		
		}
		
	}
});