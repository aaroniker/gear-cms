<template>
    <li v-bind:class="{ bell: true, active: messages.length }">
        <a href="">
            <vector src="../img/bell.svg"></vector>
        </a>
    </li>
</template>

<script>
module.exports = {
    data() {
        return {
            messages: []
        }
    },
    created() {
        var self = this;
        self.display();
        self.$visibility.every(1000, function() {
            self.display();
        });
    },
    methods: {
        display() {
            var self = this;
            self.$api.post('index.php', {
                'method': 'getMessages'
            }).then(function(response) {
                self.messages = response.data;
            }).catch(function(error) {
                self.messages = error;
            });
        }
    }
}
Vue.component('messages', function(resolve) {
    resolve(module.exports);
});
</script>
