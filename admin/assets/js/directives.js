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

Vue.directive("drag", {
    bind: function() {

        var self = this;

        this.data = {};

        this.dragstart = function(e) {

            e.dataTransfer.effectAllowed = "all";

            $(self.el).addClass("drag");

            e.dataTransfer.setData("data", JSON.stringify(self.data));
            e.dataTransfer.setData("tag", self.arg);

            return false;

        };

        this.dragend = function(e) {

            $(self.el).removeClass("drag");

            return false;

        };

        this.el.setAttribute("draggable", true);

        Vue.util.on(this.el, "dragstart", this.dragstart);
        Vue.util.on(this.el, "dragend", this.dragend);

    },
    unbind: function() {

        this.el.setAttribute("draggable", false);

        Vue.util.off(this.el, "dragstart", this.dragstart);
        Vue.util.off(this.el, "dragend", this.dragend);

    },
    update: function(value, old) {
        this.data = value;
    }
});

Vue.directive("drop", {
    acceptStatement: true,
    bind: function() {

        var self = this;

        this.dragenter = function(e) {

            $(self.el).addClass("dragActive");

            return false;

        };

        this.dragover = function(e) {

            if(e.preventDefault) {
                e.preventDefault();
            }

            $(self.el).addClass("dragActive");

            return false;

        };

        this.dragleave = function(e) {

            $(self.el).removeClass("dragActive");

            return false;

        };

        this.drop = function(e) {

            if(e.preventDefault) {
                e.preventDefault();
            }

            $(self.el).removeClass("dragActive");

            var data = e.dataTransfer.getData("data");

            self.handler(JSON.parse(data));

            return false;

        };

        Vue.util.on(this.el, "dragenter", this.dragenter);
        Vue.util.on(this.el, "dragleave", this.dragleave);
        Vue.util.on(this.el, "dragover", this.dragover);
        Vue.util.on(this.el, "drop", this.drop);

    },
    unbind: function() {

        Vue.util.off(this.el, "dragenter", this.dragenter);
        Vue.util.off(this.el, "dragleave", this.dragleave);
        Vue.util.off(this.el, "dragover", this.dragover);
        Vue.util.off(this.el, "drop", this.drop);

    },
    update: function(value, old) {

        var vm = this.vm;

        this.handler = function(data) {

            vm.$dropdata = data;

            var res = value(data);

            vm.$dropdata = null;

            return res;

        };

    }
});
