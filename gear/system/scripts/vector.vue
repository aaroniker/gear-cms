<template>
    <div v-once v-html="src"></div>
</template>

<script>
export default {
    props: [
        'src'
    ]
}
</script>
