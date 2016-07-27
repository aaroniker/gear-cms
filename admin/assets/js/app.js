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

Vue.component('file-table', {
    template: '#file-table-template',
    props: {
        data: [],
        path: '/',
        filterKey: ''
    },
    data: function () {
        return {
            checked: []
        };
    },
    created: function () {
        this.fetch();
        //get session path
        this.$watch('path', function (path) {
            this.fetch();
            //set session
        });
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
                        checked.push(loop.name);
                    });
                }

                this.checked = checked;
            }
        },
        breadcrumbs: function () {

            var path = '';

            crumb = this.path.split('/').filter(function(str) {
                return str.length;
            }).map(function(part) {
            	return {path: path += '/' + part + '/', name: part};
            });

            return crumb;

        },
        filtered: function () {
            return this.$eval('data | filterBy filterKey');
        }
    },
    methods: {
        fetch: function() {

            var vue = this;

            $.ajax({
                method: "POST",
                url: url + "admin/content/storage",
                dataType: "json",
                data: {
                    path: vue.path
                }
            }).done(function(data) {
                vue.$set('data', data);
            });

        },
        setPath: function (path) {
            this.$set('path', path);
        }
    }
});

$(document).ready(function() {

    function toggleNav(open) {

        var expand = $(this);
        var overlay = $("#overlay");
        var nav = $("#nav");
        var active = expand.hasClass('active');

        expand.toggleClass('active');

        if(open) {
            $("body").addClass('fix');
            overlay.fadeIn(200, function() {
                nav.animate({
                    left: 0
                }, 200);
            });
        } else {
            nav.animate({
                left: -220
            }, 200, function() {
                overlay.fadeOut(200, function() {
                    $("body").removeClass('fix');
                });
            });
        }

    }

    $("#head .expand").click(function() {
        toggleNav(!$(this).hasClass('active'));
    });

    $("#overlay").click(function() {
        toggleNav(false);
    });

});
