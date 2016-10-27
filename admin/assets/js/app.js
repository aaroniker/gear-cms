function randomPassword(length) {

    var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
    var pass = "";

    for(var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }

    return pass;

}

var eventHub = new Vue();

Vue.filter("lang", function(value) {
    if(value in lang) {
        return lang[value];
    } else {
        return value;
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
            startRow: 0,
            rowsPerPage: 8,
            sortKey: '',
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
        movePages: function(amount) {
            var newStartRow = this.startRow + (amount * this.rowsPerPage);
            if(newStartRow >= 0 && newStartRow < this.data.length) {
                this.startRow = newStartRow;
            }
        },
        resetStartRow: function() {
            this.startRow = 0;
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
    }
});

Vue.component("file-table", {
    template: "#file-table-template",
    props: {
        showSearch: true,
        headline: "",
        filterKey: "",
        path: "/",
        select: false,
        ext: [],
        fileName: ""
    },
    data: function() {
        return {
            tableData: [],
            editFile: false,
            editFileID: '',
            editFileName: '',
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
                return this.tableData ? this.checked.length == this.tableData.length &&  this.tableData.length > 0 : false;
            },
            set: function(value) {
                var checked = [];

                if(value) {
                    this.tableData.forEach(function(loop) {
                        checked.push(loop.id);
                    });
                }

                this.checked = checked;
            }
        },
        breadcrumbs: function() {

            var path = "";
            var str = "";

            if(this.path) {
                str = this.path.split("/").filter(function(str) {
                    return str.length;
                }).map(function(part) {
            	    return {
                        path: path += "/" + part + "/",
                        name: part
                    };
                });
            }

            return str;

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
        },
        move: function(path, dropdata) {

            var vue = this;

            $.ajax({
                method: "POST",
                url: url + "admin/content/media/move",
                data: {
                    path: path,
                    file: dropdata.id,
                    name: dropdata.name
                },
                success: function() {
                    vue.fetch();
                }
            });

        },
        edit: function() {

            var vue = this;

            $.ajax({
                method: "POST",
                url: url + "admin/content/media/edit",
                data: {
                    path: vue.path,
                    file: vue.editFileID,
                    name: vue.editFileName
                },
                success: function() {
                    vue.editFile = false;
                    vue.fetch();
                }
            });

        },
        selectFile: function(path) {

            var vue = this;

            if(!path) {
                path = "";
                this.$dispatch("addMediaModal", false);
                this.$dispatch("fileName", path);
                this.fileName = path;
            } else {
                var ext = path.split(".").pop();
                if(vue.ext.indexOf(ext) > -1 || !vue.ext.length) {
                    this.$dispatch("addMediaModal", false);
                    this.$dispatch("fileName", path);
                    this.fileName = path;
                } else {
                    alert(lang["file_select_wrong_ext"] + " " + vue.ext);
                }
            }

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

Vue.component("modal", {
    template: "#modal-template"
});

Vue.component("searchbox", {
    template: "#searchbox-template",
    data: function() {
        return {
            searchBoxShow: false,
            searchBox: "",
            active: "",
            activeID: 0
        }
    },
    props: [
        "list",
        "val",
        "id"
    ],
    methods: {
        toggleSearchBox: function() {
            this.searchBoxShow = !this.searchBoxShow;
        },
        setActive: function(active, activeID) {

            this.active = active;
            this.activeID = activeID;

            eventHub.$emit("setSearchbox", {
                name: active,
                id: activeID
            });

            this.searchBoxShow = false;
        }
    },
    computed: {
        data: function() {
            var self = this;
            return self.list.filter(function(entry) {
                return entry[self.val].toLowerCase().indexOf(self.searchBox.toLowerCase()) !== -1;
            });
        }
    }
});

var formMedia =  document.getElementsByClassName('formMedia');
if(typeof(formMedia) != 'undefined' && formMedia != null && formMedia.length) {
    new Vue({
        el: ".formMedia",
        data: {
            search: "",
            headline: "list",
            addMediaModal: false,
            fileName: false
        },
        events: {
            fileName: function(data) {
                this.fileName = data;
            },
            addMediaModal: function(data) {
                this.addMediaModal = data;
            }
        }
    });
}
