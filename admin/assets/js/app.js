Vue.filter("lang", function(value) {
    if(value in lang) {
        return lang[value];
    } else {
        return value;
    }
});

Vue.directive('drag-and-drop', {
    params: [
        'drag-and-drop',
        'drag-start',
        'drag',
        'drag-over',
        'drag-enter',
        'drag-leave',
        'drag-end',
        'drop'
    ],
    bind: function () {

        this.vm._dragSrcEl = null;

        this.handleDragStart = function(e) {

            e.target.classList.add('dragging');

            this.vm._dragSrcEl = e.target;

            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text', '*');

            if(typeof(this.vm[this.params.dragStart]) === 'function') {
                this.vm[this.params.dragStart].call(this, e.target);
            }

        }.bind(this);

        this.handleDragOver = function(e) {

            if(e.preventDefault) {
                e.preventDefault();
            }

            e.dataTransfer.dropEffect = 'move';
            e.target.classList.add('drag-over');

            if(typeof(this.vm[this.params.dragOver]) === 'function') {
                this.vm[this.params.dragOver].call(this, e.target);
            }

            return false;

        }.bind(this);

        this.handleDragEnter = function(e) {

            if(typeof(this.vm[this.params.dragEnter]) === 'function') {
                this.vm[this.params.dragEnter].call(this, e.target);
            }

            e.target.classList.add('drag-enter');

        }.bind(this);

        this.handleDragLeave = function(e) {

            if(typeof(this.vm[this.params.dragLeave]) === 'function') {
                this.vm[this.params.dragLeave].call(this, e.target);
            }

            e.target.classList.remove('drag-enter');

        }.bind(this);

        this.handleDrag = function(e) {

            if(typeof(this.vm[this.params.drag]) === 'function') {
                this.vm[this.params.drag].call(this, e.target);
            }

        }.bind(this);

        this.handleDragEnd = function(e) {

            e.target.classList.remove('dragging', 'drag-over', 'drag-enter');

            if(typeof(this.vm[this.params.dragEnd]) === 'function') {
                this.vm[this.params.dragEnd].call(this, e.target);
            }

        }.bind(this);

        this.handleDrop = function(e) {

            e.preventDefault();

            if(e.stopPropagation) {
                e.stopPropagation();
            }

            if(this.vm._dragSrcEl != e.target) {
                if(typeof(this.vm[this.params.drop]) === 'function') {

                    var el = (e.target.draggable) ? e.target : e.target.parentElement;

                    this.vm[this.params.drop].call(this, this.vm._dragSrcEl, el);

                }
            }

            return false;

        }.bind(this);

        this.el.setAttribute('draggable', 'true');

        this.el.addEventListener('dragstart', this.handleDragStart, false);
        this.el.addEventListener('dragenter', this.handleDragEnter, false);
        this.el.addEventListener('dragover', this.handleDragOver, false);
        this.el.addEventListener('drag', this.handleDrag, false);
        this.el.addEventListener('dragleave', this.handleDragLeave, false);
        this.el.addEventListener('drop', this.handleDrop, false);
        this.el.addEventListener('dragend', this.handleDragEnd, false);

    },
    update: function (newValue, oldValue) {
    // console.log(this);
    },
    unbind: function () {

        this.el.classList.remove('dragging', 'drag-over', 'drag-enter');

        this.el.removeAttribute('draggable');

        this.el.removeEventListener('dragstart', this.handleDragStart);
        this.el.removeEventListener('dragenter', this.handleDragEnter);
        this.el.removeEventListener('dragover', this.handleDragOver);
        this.el.removeEventListener('dragleave', this.handleDragLeave);
        this.el.removeEventListener('drag', this.handleDrag);

    }
});

Vue.directive("slot", {
    bind: function() {
        var host = this.vm;
        var root = host.$root;
        var raw = host._context._directives.filter(function(value) {
            return !(value.Component === undefined);
        })[0].el;

        for(var i = 0; i < raw.children.length; i++) {
            var node = raw.children[i].cloneNode(true);
            this.el.appendChild(node);
            root.$compile(node, host, this._scope);
        }
    }
});

Vue.component("table-cell", {
    template: '<td :class="class"><slot></slot></td>',
    props: {
        class: ''
    }
});

Vue.component("data-table", {
    template: "#table-template",
    props: {
        data: [],
        columns: [],
        showSearch: true,
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
            this.$dispatch("checked", this.checked);
        },
        headline: function() {
            this.$dispatch("headline", {
                headline: this.headline,
                showSearch: this.showSearch
            });
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
            return this.$eval("data | filterBy filterKey");
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
                this.headline = data.length + " " + lang["selected"] + "<a href='?delete=" + this.checked + "' class='icon delete icon-ios-trash-outline'></a>";
                this.showSearch = false;
            } else {
                this.headline = this.oldHeadline;
                this.showSearch = true;
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

Vue.component("file-table", {
    template: "#file-table-template",
    props: {
        data: [],
        columns: [],
        showSearch: true,
        headline: "",
        filterKey: "",
        path: "/"
    },
    data: function() {
        return {
            oldHeadline: "",
            checked: []
        };
    },
    created: function() {

        this.oldHeadline = this.headline;

        if($.session.get("fileTablePath")) {
            this.path = $.session.get("fileTablePath");
        }

        var vue = this;

        $(document).on("fetch", function() {
            vue.fetch();
        });

    },
    watch: {
        checked: function() {
            this.$dispatch("checked", this.checked);
        },
        headline: function() {
            this.$dispatch("headline", {
                headline: this.headline,
                showSearch: this.showSearch
            });
        },
        path: function() {
            this.$dispatch("path", this.path);
            $.session.set("fileTablePath", this.path);
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
                        checked.push(loop.id);
                    });
                }

                this.checked = checked;
            }
        },
        breadcrumbs: function() {

            var path = "";
            var crumb = "";

            if(this.path) {
                crumb = this.path.split("/").filter(function(str) {
                    return str.length;
                }).map(function(part) {
            	    return {path: path += "/" + part + "/", name: part};
                });
            }

            return crumb;

        },
        filtered: function() {
            return this.$eval("data | filterBy filterKey");
        }
    },
    methods: {
        fetch: function() {

            this.checked = [];

            var vue = this;

            $.ajax({
                method: "POST",
                url: url + "admin/content/media/get",
                dataType: "json",
                data: {
                    path: vue.path
                },
                success: function(data) {
                    vue.$set("data", data);
                }
            });

        },
        setPath: function(path) {
            this.checked = [];
            this.$set("path", path);
        }
    },
    events: {
        checked: function(data) {
            if(data.length) {
                this.headline = data.length + " " + lang["selected"] + "<a href='" + url + "admin/content/media/?delete=" + this.checked + "' class='icon delete ajax icon-ios-trash-outline'></a>";
                this.showSearch = false;
            } else {
                this.headline = this.oldHeadline;
                this.showSearch = true;
            }
        },
        fetchData: function() {
            this.fetch();
        }
    }
});

Vue.transition("fade", {
    enterClass: "fadeInDown",
    leaveClass: "fadeOutUp"
});

Vue.component("modal", {
    template: "#modal-template",
    props: {
        show: {
            type: Boolean,
            required: true,
            twoWay: true
        }
    },
    watch: {
        show: function() {
            if(this.show) {
                $("#overlay").fadeIn(200);
            } else {
                $("#overlay").fadeOut(200);
            }
        }
    }
});
