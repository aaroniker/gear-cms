function install(Vue) {

    var config = window.$gear;

    Vue.config.debug = false;

    require('vue-resource');

    Vue.http.options.root = config.url;
    Vue.http.options.emulateHTTP = true;

    new Vue({
        el: '#gear'
    });

}

if(window.Vue) {
    Vue.use(install);
}
