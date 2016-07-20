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
