<template>
    <div>
        {{ test | lang }}
    </div>
</template>

<script>
module.exports = {
    data() {
        return {
            test: 'messages'
        }
    },
    methods: {
    }
}
Vue.component('messages', function(resolve) {
    resolve(module.exports);
});
</script>
