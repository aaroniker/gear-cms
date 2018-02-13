<template>
    <div>
        <a href="">
            <vector src="../img/bell.svg"></vector>
        </a>
    </div>
</template>

<script>
module.exports = {
    data() {
        return {
        }
    },
    methods: {
    }
}
Vue.component('messages', function(resolve) {
    resolve(module.exports);
});
</script>
