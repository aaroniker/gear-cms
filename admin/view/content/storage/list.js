new Vue({
    el: '#storage',
    data: {
        path: '/',
        tableData: [],
        tableColumns: ['name', 'size'],
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
                url: "/admin/content/storage",
                dataType: "json",
                data: {
                    path: vue.path
                }
            }).done(function(data) {
                vue.$set('tableData', data);
            });

        }
    }
});
