function install(Vue) {

    var gear = window.$gear;
    var lang = window.$lang;

    Vue.config.debug = gear.debug;

    var VueResource = require('vue-resource');

    Vue.use(VueResource);

    Vue.http.options.root = gear.url;
    Vue.http.options.emulateHTTP = true;

    function getLang(name) {
        if(name in lang) {
            return lang[name];
        }
        return name;
    }

    Vue.filter('lang', function(name) {
        return getLang(name);
    });

    Vue.prototype.$lang = function(name) {
        return getLang(name);
    };

    new Vue({
        el: '#gear'
    });

}

if(window.Vue) {
    Vue.use(install);
}
