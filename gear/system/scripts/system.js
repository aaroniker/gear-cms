import { GTable, GColumn } from './table'
import { GForm } from './form'
import { GSelect } from './select'
import { GDropdown } from './dropdown'

let install = Vue => {

    let gear = Vue.prototype.$gear = window.$gear,
        lang = Vue.prototype.$lang = window.$lang;

    Vue.config.debug = gear.debug;
    Vue.config.productionTip = gear.debug;

    let axios = require('axios'),
        vsprintf = require('sprintf-js').vsprintf;

    Vue.prototype.$api = axios.create({
        baseURL: gear.url + gear.adminURL + gear.apiURL,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    let getLang = (name, array) => {
        if(name in lang) {
            return (array !== undefined) ? vsprintf(lang[name], array) : lang[name];
        }
        return (array !== undefined) ? vsprintf(name, array) : name;
    };

    Vue.prototype.$message = (message, type, stay) => {
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
            GForm,
            GSelect,
            GDropdown
        }
    });

}

if(window.Vue) {
    Vue.use(install);
}
