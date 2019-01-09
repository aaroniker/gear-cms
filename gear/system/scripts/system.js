import { GTable, GColumn } from './table'
import { GForm } from './form'

function install(Vue) {

    var gear = window.$gear;
    var lang = window.$lang;

    Vue.config.debug = gear.debug;
    Vue.config.productionTip = gear.debug;

    var axios = require('axios'),
        vsprintf = require('sprintf-js').vsprintf;

    Vue.prototype.$api = axios.create({
        baseURL: gear.url + '/api',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    function getLang(name, array) {
        if(name in lang) {
            return (array !== undefined) ? vsprintf(lang[name], array) : lang[name];
        }
        return (array !== undefined) ? vsprintf(name, array) : name;
    }

    Vue.prototype.$message = function(message, type, stay) {
        return this.$api.post('/addMessage', {
            message: {
                message: message,
                type: type,
                stay: stay
            }
        });
    };

    Vue.filter('lang', getLang);

    Vue.prototype.$lang = getLang;

    Vue.prototype.$visibility = require('visibilityjs');

    new Vue({
        el: '#gear',
        components: {
            GTable,
            GColumn,
            GForm
        }
    });

}

if(window.Vue) {
    Vue.use(install);
}
