Vue.filter('lang', function(value) {
    if(value in lang) {
        return lang[value];
    } else {
        return value;
    }
});

Vue.directive('slot', {
    bind: function() {
        var host = this.vm;
        var root = host.$root;
        var raw = host.$options._content;

        for(var i = 0; i < raw.children.length; i++) {
            var node = raw.children[i].cloneNode(true);
            this.el.appendChild(node);
            root.$compile(node, host, this._scope);
        }
    }
});

Vue.component('table-cell', {
    template: '<td :class="class"><slot></slot></td>',
    props: {
        class: ''
    }
});

Vue.component('data-table', {
    template: '#table-template',
    props: {
        data: [],
        columns: [],
        headline: '',
        filterKey: ''
    },
    data: function() {
        var sortOrders = {};
        this.columns.forEach(function(key) {
            sortOrders[key] = -1;
        });
        return {
            checked: [],
            sortKey: '',
            currentPage: 0,
            perPage: 8,
            resultCount: 0,
            oldHeadline: '',
            sortOrders: sortOrders
        };
    },
    created: function() {
        this.oldHeadline = this.headline;
    },
    watch: {
        checked: function() {
            this.$dispatch('checked', this.checked);
        },
        headline: function() {
            this.$dispatch('headline', this.headline);
        }
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
        filtered: function() {
            return this.$eval('data | filterBy filterKey');
        },
        totalPages: function() {
            return Math.ceil(this.resultCount / this.perPage);
        }
    },
    methods: {
        sortBy: function(key) {
            if(!this.sortKey) {
                this.sortKey = key;
                this.sortOrders[key] = 1;
            } else if(this.sortOrders[key] == 1) {
                this.sortOrders[key] = -1;
            } else {
                this.sortKey = '';
                this.sortOrders[key] = 0;
            }
        },
        setPage: function(page) {
            this.currentPage = page;
        }
    },
    events: {
        checked: function(data) {
            if(data.length) {
                this.headline = data.length + " " + lang["selected"] + "<a href='?delete=" + this.checked + "' class='icon icon-ios-trash-outline'></a>";
            } else {
                this.headline = this.oldHeadline;
            }
        }
    },
    filters: {
        paginate: function(list) {

            this.resultCount = list.length;

            if (this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1;
            }

            var index = this.currentPage * this.perPage;

            return list.slice(index, index + this.perPage);

        }
    }
});

Vue.component('file-table', {
    template: '#file-table-template',
    props: {
        data: [],
        columns: [],
        headline: '',
        filterKey: '',
        path: '/'
    },
    data: function() {
        return {
            oldHeadline: '',
            checked: []
        };
    },
    created: function() {
        this.oldHeadline = this.headline;
    },
    watch: {
        checked: function() {
            this.$dispatch('checked', this.checked);
        },
        headline: function() {
            this.$dispatch('headline', this.headline);
        },
        path: function() {
            this.fetch();
        }
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
        breadcrumbs: function() {

            var path = '';

            crumb = this.path.split('/').filter(function(str) {
                return str.length;
            }).map(function(part) {
            	return {path: path += '/' + part + '/', name: part};
            });

            return crumb;

        },
        filtered: function() {
            return this.$eval('data | filterBy filterKey');
        }
    },
    methods: {
        fetch: function() {

            var vue = this;

            $.ajax({
                method: "POST",
                url: url + "admin/content/media",
                dataType: "json",
                data: {
                    path: vue.path
                }
            }).done(function(data) {
                vue.$set('data', data);
            });

        },
        setPath: function(path) {
            this.checked = [];
            this.$set('path', path);
        }
    },
    events: {
        checked: function(data) {
            if(data.length) {
                this.headline = data.length + " " + lang["selected"];
            } else {
                this.headline = this.oldHeadline;
            }
        }
    }
});

Vue.component('modal', {
    template: '#modal-template',
    props: {
        show: {
            type: Boolean,
            required: true,
            twoWay: true
        }
    }
});
