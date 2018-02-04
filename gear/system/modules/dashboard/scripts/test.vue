<template>
    <div class="test" @click="check">
        {{ test | lang }}
    </div>
</template>

<script>
module.exports = {
    data() {
        return {
            test: 'Check me'
        }
    },
    methods: {
        check() {
            this.test = 'Logout';
            console.log(this.$http);
        }
    }
}
Vue.component('test-comp', function(resolve) {
    resolve(module.exports);
});
</script>
