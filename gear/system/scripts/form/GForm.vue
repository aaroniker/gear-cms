<template>
    <form :action="action" :method="method">
        <slot></slot>
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
        }
    }
}
</script>
