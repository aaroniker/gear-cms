Vue.filter('lang', function (value) {
    if (value in lang)
        return lang[value];
    else
        return value;
});

Vue.component('data-table', {
    template: '#table-template',
    props: {
        data: [],
        columns: [],
        filterKey: ''
    },
    data: function () {
        var sortOrders = {};
        this.columns.forEach(function (key) {
            sortOrders[key] = 1
        });
        return {
            checked: [],
            sortKey: '',
            sortOrders: sortOrders
        };
    },
    computed: {
        checkAll: {
            get: function() {
                return this.data ? this.checked.length == this.data.length &&  this.data.length > 0 : false;
            },
            set: function(value) {
                var checked = [];

                if(value) {
                    this.data.forEach(function(loop) {
                        checked.push(loop.id);
                    });
                }

                this.checked = checked;
            }
        },
        columnSpan: function() {
            return this.columns.length + 1;
        },
        filtered: function () {
            return this.$eval('data | filterBy filterKey');
        }
    },
    methods: {
        sortBy: function (key) {
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        }
    }
});

$(document).ready(function() {
    $("#head .expand").click(function() {
        $("body").toggleClass('fix');
        $("#wrap").toggleClass('active');
    });
});
