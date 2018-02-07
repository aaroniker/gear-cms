function install(Vue) {

    var gear = window.$gear;
    var lang = window.$lang;

    Vue.config.debug = gear.debug;

    var axios = require('axios');
    var $ = require('jquery');

    Vue.prototype.$api = axios.create({
        baseURL: gear.url + '/' + gear.adminURL + '/',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    function getLang(name) {
        if(name in lang) {
            return lang[name];
        }
        return name;
    }

    function displayMessages(handle) {
        handle.$api.post('index.php', {
            'method': 'getMessages'
        }).then(function(response) {
            var messages = response.data;
            Object.keys(messages).forEach(function(index) {
                var div = $("<div />").addClass([
                    'message',
                    messages[index].class
                ]).text(messages[index].message);
                div.appendTo($('#gear'));
            });
        }).catch(function(error) {
            console.log(error);
        });
    }

    Vue.filter('lang', function(name) {
        return getLang(name);
    });

    Vue.prototype.$lang = function(name) {
        return getLang(name);
    };

    new Vue({
        el: '#gear',
        created() {
            displayMessages(this);
        }
    });

}

if(window.Vue) {
    Vue.use(install);
}
