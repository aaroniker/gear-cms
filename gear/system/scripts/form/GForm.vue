<template>
    <form @submit="onSubmit" :action="action" :method="method">
        <slot :data="data"></slot>
    </form>
</template>
<script>
export default {
    name: 'GForm',
    props: {
        method: {
            type: String,
            default: 'POST',
            validator: value => /(POST|GET|PUT|DELETE|PATCH)/gi.test(value)
        },
        action: {
            type: String,
            default: ''
        },
        values: {
            type: Object,
            default: '{}'
        },
        route: {
            type: String|Boolean,
            default: false
        }
    },
    data() {
        return {
            data: {}
        }
    },
    mounted() {
        this.data = this.values;
    },
    methods: {
        onSubmit(e) {

            var self = this;

            if(!self.route) {
                return true;
            }

            this.$api.post(self.route, {
                data: self.data
            }).then(function(response) {
                console.log(response);
            });

            e.preventDefault();

        }
    }
}
</script>
