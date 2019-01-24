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
        },
        redirect: {
            type: String,
            default: ''
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

            let self = this;

            if(!self.route) {
                return true;
            }

            self.$api.post(self.route, {
                data: self.data
            }).then(response => {
                if(response.status == 200 && self.redirect) {
                    window.location.replace(self.$gear.url + self.$gear.adminURL + self.redirect);
                }
            });

            e.preventDefault();

        }
    }
}
</script>
