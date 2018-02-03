<template>
    <div class="test" @click="check">
        {{ test }}
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
            this.test = 'Test';
        }
    }
}
Vue.component('test-comp', function(resolve) {
    resolve(module.exports);
});
</script>
