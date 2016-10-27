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
